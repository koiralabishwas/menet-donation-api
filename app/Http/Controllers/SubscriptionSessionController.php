<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\Services\Stripe\PaymentService;
use Illuminate\Http\JsonResponse;

class SubscriptionSessionController extends Controller
{
    public function create(DonationFormRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $paymentService = new PaymentService($validatedRequest);
        $data = $paymentService->monthlyPayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ], 201);
    }
}
