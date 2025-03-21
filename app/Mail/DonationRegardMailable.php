<?php

namespace App\Mail;

use AllowDynamicProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

#[AllowDynamicProperties] class DonationRegardMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $donorName;

    public string $donationProject;

    public string $donationAmount;

    public string $donorExternalId;

    public function __construct(string $subject, string $donationMailView, object $donationMetadata)
    {
        $this->subject = $subject;
        $this->donationMailView = $donationMailView;
        $this->donorName = $donationMetadata['donor_name'];
        $this->donationProject = $donationMetadata['donation_project'];
        $this->donationAmount = $donationMetadata['amount'];
        $this->donorExternalId = $donationMetadata['donor_external_id'];
    }

    /**
     * Get the message envelope.
     */
    public function build(): DonationRegardMailable
    {
        $subject = $this->subject;

        if (env('APP_ENV') !== 'production') {
            $subject = '(テスト)'.$subject;
        }

        return $this->subject($subject)->markdown($this->donationMailView)->with([
            'donorName' => $this->donorName,
            'donationProject' => $this->donationProject,
            'donationAmount' => $this->donationAmount,
            'donorExternalId' => $this->donorExternalId,
        ]);
    }
}
