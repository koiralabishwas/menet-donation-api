<?php

namespace App\Mail\Donor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonorCheckEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        $subject = trans('email_check.subject');

        if (env('APP_ENV') !== 'production') {
            $subject = '(テスト)'.$subject;
        }

        return $this->from('test@me-net.or.jp', 'ME-net')
            ->subject($subject)
            ->markdown('emails.donor.check_email');
    }
}
