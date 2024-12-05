<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
    public static function sendErrorMessage($request, $type, $line, $userEmail, $shortMessage, $message, $code): void
    {
        $message = [
            'mention_everyone' => ! (env('APP_ENV') === 'local'), // Mention everyone except local
            'username' => '【'.env('APP_ENV').'】'.env('APP_NAME'),
            'embeds' => [
                [
                    'title' => 'Error Report',
                    'description' => "**Type:** {$type}\n".
                        "**Route:** /{$request->path()}\n".
                        "**Code:** {$code}\n".
                        "**Line:** {$line}\n".
                        "**User Email:** {$userEmail}\n".
                        "**Short Message:** {$shortMessage}\n".
                        "**Message:** {$message}",
                    'color' => hexdec('FF0000'), // Red color for errors
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ];

        self::sendMessage($message);
    }

    public static function sendSuccessMessage($donorName, $donorEmail, $donationProject, $amount): void
    {
        $message = [
            'mention_everyone' => ! (env('APP_ENV') === 'local'), // Mention everyone except local
            'username' => '【'.env('APP_ENV').'】'.env('APP_NAME'),
            'embeds' => [
                [
                    'title' => 'Success Report',
                    'description' => "**Donor Name:** {$donorName}\n".
                        "**Donor Email:** {$donorEmail}\n".
                        "**Donation Project:** {$donationProject}\n".
                        "**Amount:** {$amount}\n",
                    'color' => hexdec('00FF00'), // Green color for success
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ];

        self::sendMessage($message);
    }

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
