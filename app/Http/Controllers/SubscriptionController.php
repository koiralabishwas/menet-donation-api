<?php

namespace App\Http\Controllers;

use App\Providers\StripeProvider;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class SubscriptionController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function cancelSubscription($subscription_id): JsonResponse
    {
        $cancelled_subscription = StripeProvider::cancelSubscription($subscription_id);

        return response()->json($cancelled_subscription);
    }
}
