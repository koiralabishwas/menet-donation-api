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
}
