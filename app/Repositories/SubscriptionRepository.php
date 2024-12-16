<?php

namespace App\Repositories;

use App\Helpers\Helpers;
use App\Models\Subscription;

class SubscriptionRepository
{
    public static function storeSubscription(object $stripeSubscriptionObject): Subscription
    {
        return Subscription::create([
            'subscription_external_id' => Helpers::CreateExternalIdfromDate(),
            'stripe_subscription_id' => $stripeSubscriptionObject->id,
            'donor_id' => $stripeSubscriptionObject->metadata->donor_id,
            'donor_external_id' => $stripeSubscriptionObject->metadata->donor_external_id,
            'donation_project' => $stripeSubscriptionObject->metadata->donation_project,
            'amount' => $stripeSubscriptionObject->metadata->amount,
            'currency' => $stripeSubscriptionObject->metadata->currency,
        ]);
    }
}
