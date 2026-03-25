<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉 Welcome to NanoLympiad!')
            ->view('emails.welcome-user')
            ->with([
                'name' => $this->user['fname'] . ' ' . $this->user['lname'],
                'email' => $this->user['email'],
                'password' => $this->user['password'],
                'loginUrl' => 'https://profile.nanolympiad.org',
            ]);
    }
}
