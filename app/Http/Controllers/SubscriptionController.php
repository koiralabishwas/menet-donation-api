<?php

namespace App\Http\Controllers;

use App\Providers\StripeProvider;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class SubscriptionController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function deleteSubscription($subscription_external_id): JsonResponse
    {
        $stripe_subscription_id = SubscriptionRepository::getStripeSubscriptionId($subscription_external_id)->stripe_subscription_id;
        $cancelled_subscription = StripeProvider::cancelSubscription($stripe_subscription_id);

        //TODO: このデータを元に、管理者にメールにて通知する。
        return response()->json($cancelled_subscription);
    }
}
