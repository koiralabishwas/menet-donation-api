<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Services\DiscordService;
use Illuminate\Database\ClassMorphViolationException;
use Illuminate\Database\DeadlockException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\LostConnectionException;
use Illuminate\Database\MultipleColumnsSelectedException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\BadMethodCallException;
use Stripe\Exception\CardException;
use Stripe\Exception\IdempotencyException;
use Stripe\Exception\InvalidArgumentException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\PermissionException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\UnknownApiErrorException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        /**************************************************************************
         * Stripe API Exceptions
         **************************************************************************/

        $exceptions->render(function (ApiConnectionException $e, Request $request) {
            $message = 'Stripe API Webhook error. Api connection error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (ApiErrorException $e, Request $request) {
            $message = 'Stripe API Webhook error. Api error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            $message = 'Stripe API Webhook error. Authentication error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (BadMethodCallException $e, Request $request) {
            $message = 'Stripe API error. Bad method call error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (CardException $e, Request $request) {
            $message = 'Stripe API error. Card error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (IdempotencyException $e, Request $request) {
            $message = 'Stripe API error. Idempotency error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (InvalidArgumentException $e, Request $request) {
            $message = 'Stripe API error. Invalid argument error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (InvalidRequestException $e, Request $request) {
            $message = 'Stripe API error. Invalid request error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (PermissionException $e, Request $request) {
            $message = 'Stripe API error. Permission error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (RateLimitException $e, Request $request) {
            $message = 'Stripe API error. Rate limit error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (SignatureVerificationException $e, Request $request) {
            $message = 'Stripe API error. Signature verification error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (UnexpectedValueException $e, Request $request) {
            $message = 'Stripe API error. Unexpected value error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        $exceptions->render(function (UnknownApiErrorException $e, Request $request) {
            $message = 'Stripe API error. Unknown API error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        /**************************************************************************
         * Google Drive API Exceptions
         **************************************************************************/

        $exceptions->render(function (\Google\Service\Exception $e, Request $request) {
            $message = 'Google Drive API error. Api error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 400);
        });

        /**************************************************************************
         * Database Exceptions
         **************************************************************************/

        $exceptions->render(function (ClassMorphViolationException $e, Request $request) {
            $message = 'Database error. Deadlock error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (DeadlockException $e, Request $request) {
            $message = 'Database error. Deadlock error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (LostConnectionException $e, Request $request) {
            $message = 'Database error. Lost connection error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            $message = 'Database error. Model not found error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (MultipleColumnsSelectedException $e, Request $request) {
            $message = 'Database error. Multiple columns selected error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (MultipleRecordsFoundException $e, Request $request) {
            $message = 'Database error. Multiple records found error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            $message = 'Database error. Query error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (RecordNotFoundException $e, Request $request) {
            $message = 'Database error. Record not found error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        $exceptions->render(function (UniqueConstraintViolationException $e, Request $request) {
            $message = 'Database error. Unique constraint error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ], 500);
        });

        /**************************************************************************
         * Validation Exceptions
         **************************************************************************/

        $exceptions->render(function (ValidationException $e, Request $request) {
            $message = 'Validation error. Validation error occurred.';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->errors(),
                'request' => $request->all(),
            ], 422);
        });

        /**************************************************************************
         * Default Exception
         **************************************************************************/

        $exceptions->render(function (Exception $e, Request $request) {
            $message = 'Internal server error. An unexpected error occurred';

            DiscordService::sendErrorMessage($e, $message);

            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'request' => $request->all(),
            ], 500);
        });

    })->create();
