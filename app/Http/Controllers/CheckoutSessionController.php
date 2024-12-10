<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\DonationFormRequest;
use App\Services\OneTimePayment;
use Illuminate\Http\JsonResponse;

class CheckoutSessionController extends Controller
{
    /**
     * @throws CustomException
     */
    public function create(DonationFormRequest $request): JsonResponse
    {
        $formData = (new OneTimePayment($request))
            ->validate()
            ->createCustomer()
            ->createPrice()
            ->storeDonor()
            ->createMetadata()
            ->createCheckoutSession();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $formData,
        ], 201);
    }
}
