<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\DonationFormRequest;
use App\Repositories\DonorRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Providers\StripeProvider;

class CheckoutSessionController extends Controller
{

    public function create(DonationFormRequest $request): JsonResponse
    {
//        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $data = $request->validated();
        // Initialize strip
        try {
            // Generate a custom external_id for tracking
            $externalId = Helpers::generateUuid();

            // Create the customer in Stripe
            // pass data['customer'] , external_id
            $stripeCustomer = StripeProvider::createCustomer($data['customer'] , $externalId);
            // Store the donor info of req and stripe customer with created externalId
            $donor = DonorRepository::storeDonor( $data , $stripeCustomer , $externalId);

            $price = StripeProvider::createPrice($data['product_id'], $data['price']);
//
            $checkoutSession = StripeProvider::createCheckoutSession($stripeCustomer->id, $price->id);




            return response()->json([
                'createdPrice' => $price,
                "donor" => $donor,
                "id" => $checkoutSession->id,
                'priceId' => $price->id,
                "checkout-session" => $checkoutSession,
                "stripe Customer" => $stripeCustomer,
            ]);

        } catch (Exception $e) {
            // Log the error and return a 500 error response
            return response()->json(['error' => $e->getMessage() , "error detail" => $e->getFile()], 500);
        }
    }
}
