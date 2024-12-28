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
    public function deleteSubscription($subscription_id): JsonResponse
    {
        $cancelled_subscription = StripeProvider::cancelSubscription($subscription_id);

        //TODO: このデータを元に、管理者にメールにて通知する。
        return response()->json($cancelled_subscription);
    }
}
