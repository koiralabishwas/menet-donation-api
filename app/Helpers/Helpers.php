<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    public static function CreateExternalIdfromDate(): string
    {
        $fullYear = date('Y');
        $date = date('md');

        $randomPart = Str::upper(Str::random(6));

        return "$fullYear$date-$randomPart";
    }

    public static function createUuid(): string
    {
        return Str::uuid()->toString();
    }
}
