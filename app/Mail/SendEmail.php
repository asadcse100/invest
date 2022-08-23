<?php

namespace App\Mail;

use App\Enums\EmailRecipientType;
use App\Models\EmailTemplate;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Shortcut;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $slug;
    private $template;
    private $order;
    private $shortcut;
    private $others;

    /**
     * Create a new message instance.
     *
     * @param $slug
     * @param EmailTemplate $template
     * @param null $user
     * @param Transaction|null $order
     * @param null $others
     */
    public function __construct($slug, EmailTemplate $template, $user = null, $order = null, $others = null)
    {
        $this->user = $this->setUser($user);
        $this->slug = $slug;
        $this->template = $template;
        $this->order = $order;
        $this->setShortcut();
        $this->others = $others;
    }

    /**
     * @param $user
     * @return User|\Illuminate\Support\Collection
     * @version 1.0.0
     * @since 1.0
     */
    private function setUser($user)
    {
        if ($user instanceof User) {
            return $user;
        } elseif(is_array($user)) {
            return collect($user);
        } else {
            return collect([]);
        }
    }

    /**
     *@version 1.0.0
     * @since 1.0
     */
    private function setShortcut()
    {
        $shortcut = new Shortcut();
        if (!blank($this->order)) {
            $shortcut = $shortcut->setOrderShortcuts($this->order);
        }

        if (!blank($this->user)) {
            $shortcut = $shortcut->setUserShortcuts($this->user);
        }

        $this->shortcut = $shortcut;
    }

    /**
     * @return array
     * @version 1.0.0
     * @since 1.0
     */
    private function getContent()
    {
        $content['greeting'] = $this->shortcut->processContent(data_get($this->template, 'greeting'));
        $content['content'] = $this->shortcut->processContent(data_get($this->template, 'content'));
        $content['global_footer'] = $this->shortcut->processContent(sys_settings('mail_global_footer'));
        $content['user'] = $this->user;
        $content['order'] = $this->order;
        $content['template'] = $this->template;
        $content['others'] = $this->others;
        return $content;
    }

    /**
     * @return false
     * @version 1.0.0
     * @since 1.0
     */
    private function getCcAddress()
    {
        return false;
    }

    /**
     * @return false
     * @version 1.0.0
     * @since 1.0
     */
    private function getBccAddress()
    {
        return false;
    }

    /**
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    private function getSubject()
    {
        return $this->shortcut->processContent(data_get($this->template, 'subject'));
    }

    /**
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    private function getViewFile()
    {
        $viewPath = null;
        if (data_get($this->template, 'recipient') == EmailRecipientType::CUSTOMER) {
            $viewPath = "emails.users.".$this->slug;
        } else {
            $viewPath = "emails.admin.".$this->slug;
        }

        if (view()->exists($viewPath)) {
            return $viewPath;
        }

        return "emails.layouts.content";
    }

    /**
     * Build the message.
     *
     * @return $this
     * @version 1.0.0
     * @since 1.0
     */
    public function build()
    {
        $email = $this->subject($this->getSubject());

        if ($cc = $this->getCcAddress()) {
            $email = $email->cc($cc);
        }

        if ($bcc = $this->getBccAddress()) {
            $email = $email->bcc($bcc);
        }

        return $email->from(sys_settings('mail_from_email'), sys_settings('mail_from_name'))
        ->view($this->getViewFile(), $this->getContent());
    }
}
