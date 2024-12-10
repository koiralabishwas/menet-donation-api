<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\PaymentServices\OneTimePayment;
use Illuminate\Http\JsonResponse;

class CheckoutSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {
        $oneTimePayment = new OneTimePayment($request);
        $formData = $oneTimePayment->processPayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $formData,
        ], 201);
    }
}
