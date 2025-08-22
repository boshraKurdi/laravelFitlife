<?php
// app/Mail/VerifyEmailCodeMail.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\User;

class VerifyEmailCodeMail extends Mailable
{
    public function __construct(public User $user, public string $code) {}

    public function build()
    {
        return $this->subject('Mail confirmation code')
            ->view('emails.verify-code')
            ->with(['user' => $this->user, 'code' => $this->code]);
    }
}
