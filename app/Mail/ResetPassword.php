<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\PasswordReset;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $passwordreset = new PasswordReset;
        $passwordreset->email = request()->email;
        $passwordreset->token = bcrypt(str_random(60));
        $passwordreset->save();
        return $this->markdown('emails.resetpassword')->with(["token"=>$passwordreset->token]);
    }
}
