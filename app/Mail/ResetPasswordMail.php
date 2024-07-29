<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $resetLink = url('reset-password/' . $this->token . '?email=' . urlencode($this->email));
        return $this->view('emails.reset_password')
                    ->with(['email' => $this->email, 'resetLink' => $resetLink])
                    ->subject('Reset Password Notification');
    }
}
