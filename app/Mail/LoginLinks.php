<?php

namespace App\Mail;

use App\Models\Student;
use C6Digital\PasswordlessLogin\Facades\PasswordlessLogin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginLinks extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public $users,
    ) {}

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Login Links',
        );
    }

    public function content()
    {
        foreach ($this->users as $user) {
            $data[] = PasswordlessLogin::generateLoginLink($user);
        }

        return new Content(
            markdown: 'filament-passwordless-login::mail.login-links',
            with: [
                'urls' => $data,
            ]
        );
    }
}
