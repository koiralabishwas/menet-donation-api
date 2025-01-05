<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
    public static function sendErrorMessage(Exception $error, string $message): void
    {
        $message = [
            'mention_everyone' => ! (env('APP_ENV') === 'local'), // Mention everyone except local
            'username' => '【'.env('APP_ENV').'】'.env('APP_NAME'),
            'embeds' => [
                [
                    'title' => 'Error Report',
                    'description' => "\n".
                        '**Route:** /'.request()->path()."\n".
                        '**Message:** '.$message."\n".
                        '**Error:** '.$error->getMessage()."\n".
                        "**Request:** ```json\n".request()->getContent()."\n```\n",
                    'color' => hexdec('FF0000'), // Red color for errors
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ];

        self::sendMessage($message);
    }

    //    FIXME: make sendSuccessMessage

    public static function sendMessage(array $payload): void
    {
        $webhookUrl = env('DISCORD_WEBHOOK_URL');

        if (! $webhookUrl) {
            Log::error('Discord webhook URL is not set in the .env file.');

            return;
        }

        try {
            $response = Http::post($webhookUrl, $payload);

            if ($response->failed()) {
                Log::error('Failed to send message to Discord: '.$response->body());
            }
        } catch (Exception $e) {
            Log::error('Error sending message to Discord: '.$e->getMessage());
        }
    }
}
