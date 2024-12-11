<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\PaymentServices\PaymentService;
use Illuminate\Http\JsonResponse;

class CheckoutSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {
        $paymentService = new PaymentService($request);
        $formData = $paymentService->oneTimePayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $formData,
        ], 201);
    }
}
