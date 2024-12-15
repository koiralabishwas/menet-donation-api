<?php

namespace App\Http\Controllers;

use App\Services\Stripe\WebhookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 400);
        }
    }

    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        try {
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

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 400);
        }

    }

    public function invoicePaid(Request $request): JsonResponse
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
