<?php

namespace App\Services\Stripe;

use App\Enums\WebhookSecret;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;

class WebhookServices
{
    private WebhookServiceBuilder $builder;
      // TODO :
      //paymentIntentSucceed function
      //customerSubscrioptionCreated function
      //invoicePaid function
      // make better Exceptions

    public function __construct(Request $request , WebhookSecret $webhookSecret){
        $this->builder = new WebhookServiceBuilder($request,$webhookSecret);
    }

    public function paymentIntentSucceed() :array
    {
        try {
         $this->builder
            ->constructWebhookEvent()
            ->storeDonation()
            ->sendRegardMail();

            return [
                'message' => 'Success',
                'type' => 'payment_intent.succeeded',
            ];
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }

    public function customerSubscriptionCreated() : array
    {
        try {
            $this->builder
                ->constructWebhookEvent()
                ->storeSubscription()
                ->sendRegardMail();

            return [
                'message' => 'Success',
                'type' => 'customer.subscription.created',
            ];
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }




//    /**
//     * @throws SignatureVerificationException
//     * @throws UnexpectedValueException
//     */
//
//    // ↓これbuilder 処理かも
//    public static function constructWebhookEvent(Request $request, string $endpoint_secret): Event
//    {
//        $payload = $request->getContent();
//        $sig_header = $request->header('Stripe-Signature');
//
//        return Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
//    }


}
