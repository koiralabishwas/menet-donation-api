<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    public static function generateUuid() : string
    {
        $fullYear = date('Y');
        $date = date('md');

        $randomPart = Str::upper(Str::random(6));

        return "$fullYear$date-$randomPart";
    }
}
