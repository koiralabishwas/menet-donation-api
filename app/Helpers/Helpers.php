<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    public static function createUuid(): string
    {
        return Str::uuid()->toString();
    }
}
