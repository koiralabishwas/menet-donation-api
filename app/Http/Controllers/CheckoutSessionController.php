<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonationFormRequest;
use App\PaymentServices\PaymentService;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class CheckoutSessionController extends Controller
{
    /**
     * @throws ApiErrorException
     * @throws InvalidRequestException
     */
    public function create(DonationFormRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $paymentService = new PaymentService($validatedRequest);
        $data = $paymentService->oneTimePayment();

        return response()->json([
            'status' => 201,
            'message' => 'success',
            'data' => $data,
        ], 201);
    }
}
