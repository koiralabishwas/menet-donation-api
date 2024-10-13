<?php

namespace App\Mail;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

#[AllowDynamicProperties] class DonationRegardMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($donationMetadata)
    {
        $this->donorName = $donationMetadata['donor_name'];
        $this->donationProject = $donationMetadata['donation_project'];
        $this->donationAmount = $donationMetadata['amount'];
        $this->donationCertificateUrl = $donationMetadata['tax_deduction_certificate_url'];
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
