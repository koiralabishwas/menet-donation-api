<?php

use App\Services\DiscordService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (SignatureVerificationException $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Stripe API Webhook error. Invalid signature error occurred.');

            return response()->json([
                'message' => 'Stripe API Webhook error. Invalid signature error occurred.',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (UnexpectedValueException $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Stripe API Webhook error. Invalid payload error occurred.');

            return response()->json([
                'message' => 'Stripe API Webhook error. Invalid payload error occurred.',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (InvalidRequestException $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Stripe API Webhook error. Invalid payload error occurred.');

            return response()->json([
                'message' => 'Stripe API error. Invalid payload error occurred.',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (ApiErrorException $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Stripe API Webhook error. Api error occurred.');

            return response()->json([
                'message' => 'Stripe API error. Api error occurred.',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (\Google\Service\Exception $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Google Drive API error. Api error occurred.');

            return response()->json([
                'message' => 'Google Drive API error. Api error occurred.',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Validation error. Invalid request error occurred.');

            return response()->json([
                'message' => 'Validation error. Invalid request error occurred.',
                'error' => $e->errors(),
                'request' => $request->all(),
            ], 422);
        });

        $exceptions->render(function (Exception $e, Request $request) {
            DiscordService::sendErrorMessage($e, 'Internal server error. An unexpected error occurred');

            return response()->json([
                'message' => 'Internal server error. An unexpected error occurred',
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

    })->create();
