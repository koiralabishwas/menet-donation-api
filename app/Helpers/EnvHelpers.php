<?php

namespace App\Helpers;

class EnvHelpers
{
    public static function adjustUrlFromEnv(string $string): string
    {
        $env = app()->environment(['production', 'develop']);
        if ($env) {
            return $string.'.html';
        }

        return $string;

    }
}
