<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    public $token;
    public $name;

    public function __construct($token, $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Activate Your Account')
            ->view('emails.activate');
    }
}