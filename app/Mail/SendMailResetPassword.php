<?php

namespace App\Mail;

use App\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailResetPassword extends Mailable
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
        if ($resetpassword = ResetPassword::where('email', request()->email)->first()) {
            $resetpassword->delete();
        }
        $resetpassword = new ResetPassword;
        $resetpassword->email = request()->email;
        $resetpassword->token = str_random(rand(60, 200));
        $resetpassword->save();
        return $this->markdown('emails.resetpassword')->with(["token" => $resetpassword->token, "email" => request()->email]);
    }
}
