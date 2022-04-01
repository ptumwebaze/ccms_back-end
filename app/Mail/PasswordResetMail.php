<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email, $mailText, $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, string $mailText, string $subject)
    {
        $this->email = $email;
        $this->mailText = $mailText;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
            ->subject($this->subject)
            ->view('email.forward');
    }
}
