<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SendTestEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $emailTemplate;
    private $greeting;
    private $content;
    private $shortcut;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $slug
     * @throws \Exception
     */
    public function __construct(User $user, $slug)
    {
        $this->emailTemplate = $this->getTemplate($slug);
        $this->setDefaultShortcut($user);
    }

    private function getTemplate($slug)
    {
        $template = EmailTemplate::where('slug', $slug)->first();
        if (blank($template)) {
            throw new \Exception(__("Invalid email template!"));
        }
        return $template;
    }

    private function setDefaultShortcut($user)
    {
        $this->shortcut = [
            '[[site_name]]' => sys_settings('site_name'),
            '[[site_email]]' => sys_settings('site_email'),
            '[[site_url]]' => url('/'),
            '[[user_name]]' => data_get($user, 'name'),
            '[[user_id]]' => the_uid(1),
            '[[order_id]]' => 'TNX0198423498',
            '[[order_by]]' => 'Softnio',
            '[[order_amount]]' => '$50',
            '[[order_time]]' => Carbon::now()->format(sys_settings('date_format') . ' ' . sys_settings('time_format')),
            '[[order_detail]]' => '[Order Details Display Here]', 
            '[[payment_method]]' => 'PayPal',
            '[[refund_details]]' => '[Refund Details Display Here]', 
        ];
    }

    private function getContent()
    {
        $content['greeting'] = strtr(data_get($this->emailTemplate, 'greeting'), $this->shortcut);
        $content['message'] = auto_p(strtr(data_get($this->emailTemplate, 'content'), $this->shortcut));
        return $content;
    }

    private function getSubject()
    {
        return strtr(data_get($this->emailTemplate, 'subject'), $this->shortcut);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->getSubject())
            ->view('emails.admin.test')
            ->with([
                'content' => $this->getContent(),
            ]);
    }
}
