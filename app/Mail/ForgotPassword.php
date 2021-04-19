<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailable)
    {
        $this->name = $mailable->name;
        $this->email = $mailable->email;
        $this->password = $mailable->password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('forgotPassword')->subject('Clicking Nueva contraseña');
    }
}
