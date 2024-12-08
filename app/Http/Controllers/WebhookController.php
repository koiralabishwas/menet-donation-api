<?php

namespace App\Http\Controllers;

use App\Enums\LogType;
use App\Exceptions\CustomException;
use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (UnexpectedValueException $e) {
            new CustomException(LogType::ERROR, __LINE__, '', 'Invalid payload', $e);

            return response()->json([
                'message' => 'Invalid payload',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        } catch (SignatureVerificationException $e) {
            new CustomException(LogType::ERROR, __LINE__, '', 'Invalid signature', $e);

            return response()->json([
                'message' => 'Invalid signature',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        switch ($event->type) {
            // for one-time payment checkout session
            case 'payment_intent.succeeded':   // to store one-time payment on db
                $paymentIntent = $event->data;
                $metaData = $paymentIntent['object']->metadata; // onetime のときのみここにmetadata 保存されている。
                if ($metaData->type === 'ONE_TIME') {
                    Log::info($metaData->type);
                    Log::info($paymentIntent['object']); // テスㇳでつかうため　、必要
                    //ここで残りのonetime時の処理
                    DonationRepository::storeDonation($metaData, $paymentIntent['object']);
                    Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
                } else {
                    Log::info('subscription mode , no onetime 処理 will be run');
                }

                return response()->json([
                    'message' => 'Success',
                    'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                ], 200);
            case 'customer.subscription.created':
                $subscriptionData = $event->data;
                //                    Log::info("webhook case customer.subscription.created");
                //                    Log::info($subscriptionData);
                SubscriptionRepository::storeSubscription($subscriptionData['object']);

                return response()->json([
                    'message' => 'Success',
                    'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                ], 200);
            case 'invoice.paid': // to store subscription payments in db
                $invoice = $event->data;
                //                    Log::info("webhook case invoice.paid");
                //                    Log::info($data);
                DonationRepository::storeDonation($invoice['object']->subscription_details->metadata, $invoice['object']);

                return response()->json([
                    'message' => 'Success',
                    'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                ], 200);
            default:
                return response()->json([
                    'message' => 'Invalid payload',
                    'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                ], 400);
        }
    }

    public function paymentIntentSucceed(Request $request): JsonResponse // for dev and prd use
    {
        $endpoint_secret = 'whsec_T9qp3taSDglrSmrfCnHzfqC5laPRqb50'; // this differs in each endpoint
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid payload',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Invalid signature', ['exception' => $e]);

            return response()->json([
                'message' => 'Invalid signature',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        if ($event->type !== 'payment_intent.succeeded' || $event->data['object']->metadata->type !== 'ONE_TIME') {
            return response()->json([
                'message' => 'Invalid payload',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
            ], 400);
        }

        $paymentIntent = $event->data;
        $metaData = $paymentIntent['object']->metadata;

        DonationRepository::storeDonation($metaData, $paymentIntent['object']);

        try {
            Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
        } catch (Exception $e) {
            Log::error('Stripe webhook error: Failed to send email', ['exception' => $e]);

            return response()->json([
                'message' => 'Failed to send email',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'message' => 'Success',
            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        ], 200);
    }

    public function customerSubscriptionCreated(Request $request): JsonResponse
    {
        $endpoint_secret = 'whsec_mQjZ7AdLtBAFphXTugFMglLyNdPrbfAY';
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (UnexpectedValueException $e) {
            new CustomException(LogType::ERROR, __LINE__, '', 'Invalid payload', $e);

            return response()->json([
                'message' => 'Invalid payload',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        } catch (SignatureVerificationException $e) {
            new CustomException(LogType::ERROR, __LINE__, '', 'Invalid signature', $e);

            return response()->json([
                'message' => 'Invalid signature',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
                'error' => $e->getMessage(),
            ], 400);
        }

        if ($event->type !== 'customer.subscription.created') {
            return response()->json([
                'message' => 'Invalid payload',
                'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
            ], 400);
        }

        $subscriptionData = $event->data;
        SubscriptionRepository::storeSubscription($subscriptionData['object']);

        // TODO : send notification mail

        return response()->json([
            'message' => 'Success',
            'request' => ['headers' => $request->headers->all(), 'body' => $request->getContent()],
        ], 200);
    }
}
