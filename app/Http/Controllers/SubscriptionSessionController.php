<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\PaymentServices\PaymentService;
use Illuminate\Http\JsonResponse;

class SubscriptionSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {
        $paymentService = new PaymentService($request);
        $formData = $paymentService->monthlyPayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $formData,
        ], 201);
    }
}
