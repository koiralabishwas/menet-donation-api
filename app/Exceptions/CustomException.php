<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomException extends Exception
{
    protected string $type;

    protected string $userEmail;

    protected $code;

    protected int $line;

    protected string $shortMessage;

    protected $message;

    public function __construct(
        string $type,
        int $line,
        string $userEmail,
        string $shortMessage,
        Exception $e
    ) {
        parent::__construct($e->getMessage(), $e->getCode(), $e);

        $this->type = $type;
        $this->userEmail = $userEmail;
        $this->code = $e->getCode() ?: 500; // Default to 500 if no code is provided
        $this->line = $line;
        $this->shortMessage = $shortMessage;
        $this->message = $e->getMessage();
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'timestamp' => now()->toDateTimeString(),
            'route' => '/'.$request->path(),
            'error_type' => $this->type,
            'error_line' => $this->line,
            'user_email' => $this->userEmail,
            'short_message' => $this->shortMessage,
            'message' => $this->message,
        ], $this->code);
    }
}
