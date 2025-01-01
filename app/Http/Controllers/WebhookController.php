<?php

namespace App\Http\Controllers;

use App\Services\Stripe\WebhookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

class WebhookController extends Controller
{
    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function paymentIntentSucceed(Request $request): JsonResponse
    {
        $event = new WebhookService(
            $request,
            env('STRIPE_PAYMENT_INTENT_SUCCEED_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
        );
        $data = $event->paymentIntentSucceed();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        $event = new WebhookService(
            $request,
            env('STRIPE_CUSTOMER_SUBSCRIPTION_CREATED_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
        );
        $data = $event->customerSubscriptionCreated();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @throws SignatureVerificationException
     */
    public function customerSubscriptionUpdated(Request $request): JsonResponse
    {
        $event = new WebhookSErvice(
            $request,
            env('STRIPE_CUSTOMER_SUBSCRIPTION_UPDATED_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
        );

        $data = $event->customerSubscriptionUpdated();
        Log::info($request);

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ]);

    }

    /**
     * @throws SignatureVerificationException
     * @throws UnexpectedValueException
     * @throws Exception
     */
    public function invoicePaid(Request $request): JsonResponse
    {
        $event = new WebhookService(
            $request,
            env('STRIPE_INVOICE_PAID_SECRET', env('STRIPE_LOCAL_WEBHOOK_SECRET'))
        );
        $data = $event->invoicePaid();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ]);
    }
}
