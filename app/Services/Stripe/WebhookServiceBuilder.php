<?php

namespace App\Services\Stripe;

use App\Mail\DonationRegardMailable;
use App\Providers\StripeProvider;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use Exception;
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

    private bool $isSubscription;

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

    public function isSubscription(): self
    {
        $invoiceId = $this->webhookEvent->data->object->invoice;
        if ($invoiceId == null) {
            $this->isSubscription = false;

            return $this;
        }

        $invoice = StripeProvider::getInvoice($invoiceId);
        if ($invoice == null) {
            $this->isSubscription = false;
        } else {
            $this->isSubscription = true;
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function storeOneTimeDonation(): WebhookServiceBuilder
    {
        if ($this->isSubscription) {
            return $this;
        }

        $paymentIntent = $this->webhookEvent->data->object;
        $this->metaData = $paymentIntent->metadata;
        DonationRepository::storeDonation($this->metaData, $paymentIntent);

        return $this;
    }

    public function storeMonthlyDonation(): WebhookServiceBuilder
    {
        $invoice = $this->webhookEvent->data->object;
        $this->metaData = $invoice->subscription_details->metadata;
        DonationRepository::storeDonation($this->metaData, $invoice);

        return $this;
    }

    public function storeSubscription(): WebhookServiceBuilder
    {
        $subscription = $this->webhookEvent->data->object;
        $this->metaData = $subscription->metadata;
        SubscriptionRepository::storeSubscription($subscription);

        return $this;
    }

    public function updateSubscription(): WebhookServiceBuilder
    {
        $subscription = $this->webhookEvent->data->object;
        $this->metaData = $subscription->metadata;
        $cancel_at_period_end = $subscription->cancel_at_period_end;

        if ($cancel_at_period_end) {
            SubscriptionRepository::putCancelFlag($this->metaData->subscription_external_id);
            $this->sendMonthlyDonationUpdatedEmail('毎月の寄付キャンセルのお知らせ', 'mail.SubscriptionCancelledMail');
        } else {
            SubscriptionRepository::removeCancelFlag($this->metaData->subscription_external_id);
        }

        return $this;
    }

    public function sendOneTimeDonationEmail(string $subject, string $mailView): void
    {
        if ($this->isSubscription) {
            return;
        }

        $receipt = $this->metaData->donor_email;
        $metaData = $this->metaData;
        Mail::to($receipt)->send(new DonationRegardMailable(
            $subject,
            $mailView,
            $metaData
        ));
    }

    public function sendMonthlyDonationConfirmationEmail(string $subject, string $mailView): void
    {
        $receipt = $this->metaData->donor_email;
        Mail::to($receipt)->send(new DonationRegardMailable(
            $subject,
            $mailView,
            $this->metaData
        ));
    }

    public function sendMonthlyDonationUpdatedEmail(string $subject, string $mailView): void
    {
        $receipt = $this->metaData->donor_email;
        Mail::to($receipt)->send(new DonationRegardMailable(
            $subject,
            $mailView,
            $this->metaData
        ));
    }

    public function sendMonthlyDonationEmail(string $subject, string $mailView): void
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
