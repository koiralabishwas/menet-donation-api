<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
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
