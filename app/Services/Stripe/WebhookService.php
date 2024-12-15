<?php

namespace App\Services\Stripe;

use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

class WebhookService
{
    private WebhookServiceBuilder $builder;
    // TODO :
    // make better Exceptions

    public function __construct(Request $request, string $webhookSecret)
    {
        $this->builder = new WebhookServiceBuilder($request, $webhookSecret);
    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function paymentIntentSucceed(): array
    { // TODO: subscription型のpaymentの場合はイベントが発生してしまうが、回避しなければならない。
        $this->builder
            ->constructWebhookEvent()
            ->storeOneTimeDonation()
            ->sendEmail();

        return [
            'message' => 'Success',
            'type' => 'payment_intent.succeeded',
        ];
    }

    /**
     * @throws Exception
     */
    public function customerSubscriptionCreated(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeSubscription()
            ->sendEmail();

        return [
            'message' => 'Success',
            'type' => 'customer.subscription.created',
        ];
    }

    /**
     * @throws Exception
     */
    public function invoicePaid(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeMonthlyDonation()
            ->sendEmail();

        return [
            'message' => 'Success',
            'type' => 'invoice.paid',
        ];
    }
}
