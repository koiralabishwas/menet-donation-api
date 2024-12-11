<?php

namespace App\Helpers;

use App\Enums\WebhookSecret;

class EnvHelpers
{
    public static function getUrlByENV(string $string): string
    {
        $env = app()->environment(['production', 'develop']);
        if ($env) {
            return $string.'.html';
        }

        return $string;

    }

    public static function getWebhookSecret(WebhookSecret $type): string
    {
        if (app()->environment('local')) {
            return config('services.stripe.webhook.local_webhook_secret');
        }

        return config("services.stripe.$type->value}");
    }
}
