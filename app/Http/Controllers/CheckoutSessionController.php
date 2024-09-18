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
        // Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $data = $request->validated();

        try {
            $externalId = Helpers::generateUuid();

            $stripeCustomer = StripeProvider::createCustomer($data['customer'] , $externalId);
            $donor = DonorRepository::storeDonor($data, $stripeCustomer , $externalId);

            $stripePrice = StripeProvider::createPrice($data['product_id'], $data['price']);

            $checkoutSession = StripeProvider::createCheckoutSession($stripeCustomer->id, $stripePrice->id);

            $data = [
                "donor" => $donor,
                "stripe_checkout_session" => $checkoutSession,
                'stripe_price' => $stripePrice,
                "stripe_customer" => $stripeCustomer,
            ];

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $data,
            ], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage() , "error detail" => $e->getFile()], 500);
        }
    }
}
