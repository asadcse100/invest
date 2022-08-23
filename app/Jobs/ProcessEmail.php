<?php

namespace App\Jobs;

use App\Enums\EmailRecipientType;
use App\Enums\EmailTemplateStatus;
use App\Enums\UserRoles;
use App\Mail\SendEmail;
use App\Models\EmailTemplate;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $slug;
    private $user;
    private $order;
    private $mailTo;
    private $others;
    private $catchs;

    public function __construct($slug, User $user = null, $mailTo = null, $order = null, $others = null, $catchs = false)
    {
        $this->slug = $slug;
        $this->user = $user;
        $this->order = $order;
        $this->mailTo = $mailTo;
        $this->others = $others;
        $this->catchs = $catchs;
    }

    private function getTemplate($slug)
    {
        $template = EmailTemplate::where('slug', $slug)->first();
        if (blank($template)) {
            throw new \Exception(__("Invalid email template!"));
        }
        return $template;
    }

    private function setMailToForAdmin($template)
    {
        $recipient = data_get($template, 'addresses.recipient', null);

        if (!empty((int)$recipient)) {
            $getUser = User::find($recipient);
            if($getUser) {
                $this->user = $getUser;
                $this->mailTo = $getUser->email;
            } else {
                $this->mailTo = get_email_recipient('default');
            }
        } elseif ($recipient == 'custom') {
            $this->mailTo = (data_get($template, 'addresses.custom')) ? data_get($template, 'addresses.custom') : get_email_recipient('default');
        } elseif ($recipient == 'alternet') {
            $this->mailTo = get_email_recipient('alternet');
        } else {
            $this->mailTo = get_email_recipient('default');
        }
    }

    public function handle()
    {
        try {
            $template = $this->getTemplate($this->slug);

            if (data_get($template, 'recipient') == EmailRecipientType::ADMIN) {
                $this->setMailToForAdmin($template);
            }

            if ($template->status == EmailTemplateStatus::ACTIVE) {
                Mail::to($this->mailTo ?? $this->user->email)->send(new SendEmail($this->slug, $template, $this->user, $this->order, $this->others));
            }
        } catch (\Exception $e) {
            save_mailer_log($e, $this->slug);

            if ($this->catchs == true) {
                throw new \Exception($e->getMessage());
            }
        }
    }
}
