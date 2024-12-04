<?php

namespace App\Http\Controllers;

use App\Mail\DonationRegardMailable;
use App\Repositories\DonationRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    /**
     * @throws SignatureVerificationException
     */
    public function create(Request $request): void // NOTE!:to test for local use
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
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
                    Log::info('subscription mode , no onetime 処理 will be run ');
                }

                return;
                // catch and store subscription on db
            case 'customer.subscription.created':
                $subscriptionData = $event->data;
                //                    Log::info("webhook case customer.subscription.created");
                //                    Log::info($subscriptionData);
                SubscriptionRepository::storeSubscription($subscriptionData['object']);

                return;
                //
            case 'invoice.paid': // to store subscription payments in db
                $invoice = $event->data;
                //                    Log::info("webhook case invoice.paid");
                //                    Log::info($data);
                DonationRepository::storeDonation($invoice['object']->subscription_details->metadata, $invoice['object']);

                // TODO:確認メールとキャンセルurlもかねて送る？
                return;

            default:
        }
    }

    /**
     * @throws SignatureVerificationException
     */
    public function paymentIntentSucceed(Request $request): void // for dev and prd use
    {
        $endpoint_secret = 'whsec_T9qp3taSDglrSmrfCnHzfqC5laPRqb50'; // this differs in each endpoint
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );

        if ($event->type !== 'payment_intent.succeeded') {
            return;
        }

        $paymentIntent = $event->data;
        $metaData = $paymentIntent['object']->metadata;
        Log::info($paymentIntent['object']); // テスㇳでつかうため　、必要

        DonationRepository::storeDonation($metaData, $paymentIntent['object']);
        Mail::to($paymentIntent['object']->receipt_email)->send(new DonationRegardMailable($metaData));
    }

    /**
     * @throws SignatureVerificationException
     */
    public function customerSubscriptionCreated(Request $request): void
    {
        $endpoint_secret = 'whsec_mQjZ7AdLtBAFphXTugFMglLyNdPrbfAY';
        $payload = $request->getcontent();
        $sig_header = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        if ($event->type !== 'customer.subscription.created') {
            return;
        }

        $subscriptionData = $event->data;
        SubscriptionRepository::storeSubscription($subscriptionData['object']);
        // TODO : send notification mail
    }
}
