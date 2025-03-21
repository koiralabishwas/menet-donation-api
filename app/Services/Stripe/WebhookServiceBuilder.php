<?php

namespace App\Services\Stripe;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use App\Repositories\DonorRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class WebhookServiceBuilder
{
    private Request $request;

    private string $webhookSecret;

    private Event $webhookEvent;

    private object $metaData;

    public function __construct(Request $request, string $webhookSecret)
    {
        $this->request = $request;
        $this->webhookSecret = $webhookSecret;
    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     */
    public function constructWebhookEvent(): WebhookServiceBuilder
    {
        $payload = $this->request->getContent();
        $sig_header = $this->request->header('Stripe-Signature');
        $endpoint_secret = $this->webhookSecret;

        $this->webhookEvent = Webhook::constructEvent(
            $payload,
            $sig_header,
            $endpoint_secret,
        );

        return $this;
    }

    public function updateCustomer(): WebhookServiceBuilder
    {
        $customer = $this->webhookEvent->data->object;
        DonorRepository::updateDonor($customer);

        return $this;
    }

    public function storeDonation(): WebhookServiceBuilder
    {
        $invoice = $this->webhookEvent->data->object;
        if (! empty($invoice->subscription)) {
            $this->metaData = $invoice->subscription_details->metadata;
            DonationRepository::storeDonation($this->metaData);
            $this->sendMail(
                '今月分の寄付完了のお知らせ',
                'mail.SubscriptionPaidMail'
            );
        } else {
            $this->metaData = $invoice->metadata;
            DonationRepository::storeDonation($this->metaData);
            $this->sendMail(
                '寄付完了のお知らせ',
                'mail.donationRegard'

            );
        }

        return $this;
    }

    public function createOrUpdateSubscription(): WebhookServiceBuilder
    {
        $subscription = $this->webhookEvent->data->object;
        $this->metaData = $subscription->metadata;
        $cancel_at_period_end = $subscription->cancel_at_period_end;

        SubscriptionRepository::StoreOrUpdate($subscription, $cancel_at_period_end);
        if ($cancel_at_period_end) {
            $this->sendMail('毎月型寄付のキャンセルのお知らせ', 'mail.SubscriptionCancelledMail');
        } else {
            $this->sendMail('毎月型寄付確定のお知らせ', 'mail.SubscriptionCreatedMail');
        }

        return $this;
    }

    public function sendMail(string $subject, string $mailView): void
    {
        $receipt = $this->metaData->donor_email;
        $metaData = $this->metaData;
        Mail::to($receipt)->send(new DonationRegardMailable(
            $subject,
            $mailView,
            $metaData
        ));
    }
}
