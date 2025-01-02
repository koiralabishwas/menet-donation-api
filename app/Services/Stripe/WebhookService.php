<?php

namespace App\Services\Stripe;

use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;

class WebhookService
{
    private WebhookServiceBuilder $builder;
    // TODO : make better Exceptions

    public function __construct(Request $request, string $webhookSecret)
    {
        $this->builder = new WebhookServiceBuilder($request, $webhookSecret);
    }

    /**
     * @throws Exception
     */
    public function customerSubscriptionCreated(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeSubscription();

        return [
            'message' => 'Success',
            'type' => 'customer.subscription.created',
        ];
    }

    /**
     * @throws SignatureVerificationException
     */
    public function customerSubscriptionUpdated(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->updateSubscription();

        //TODO : updateSubscriptionInfo -> sendUpdatedInfoMail to user
        return [
            'message' => 'Success',
            'type' => 'customer.subscription.updated',
        ];

    }

    /**
     * @throws Exception
     */
    public function invoicePaid(): array
    {
        $this->builder
            ->constructWebhookEvent()
            ->storeDonation();

        return [
            'message' => 'Success',
            'type' => 'invoice.paid',
        ];
    }
}
