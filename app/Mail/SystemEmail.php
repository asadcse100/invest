<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    private $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $template)
    {
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view =  (view()->exists('emails.'.$this->template)) ? 'emails.'.$this->template : 'emails.admin.test';

        return $this->subject($this->data['subject'])->view($view);
    }
}
