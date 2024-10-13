<?php

namespace App\Mail;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

#[AllowDynamicProperties] class DonationRegardMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(string $donorName , string $donationProject , int $donationAmount , string $donationCertificateUrl)
    {
        $this->donorName = $donorName;
        $this->donationProject = $donationProject;
        $this->donationAmount = $donationAmount;
        $this->donationCertificateUrl = $donationCertificateUrl;
    }

    /**
     * Get the message envelope.
     */
   public function build(): DonationRegardMailable
   {
       $subject = '寄付完了のお知らせ';

       return $this->subject($subject)->markdown('mail.donationRegard')->with([
           'donorName' => $this->donorName,
           'donationProject' => $this->donationProject,
           'donationAmount' => $this->donationAmount,
           'donationCertificateUrl' => $this->donationCertificateUrl,
       ]);
   }
}
