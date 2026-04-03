<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class QueuedMailer
{
    public static function to(string $email, Mailable $mail): void
    {
        Mail::to($email)->queue($mail);
    }
}
