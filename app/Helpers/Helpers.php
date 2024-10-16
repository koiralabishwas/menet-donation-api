<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Helpers
{
    public static function CreateExternalIdfromDate() : string
    {
        $fullYear = date('Y');
        $date = date('md');

        $randomPart = Str::upper(Str::random(6));

        return "$fullYear$date-$randomPart";
    }

    public static function createUuid() : String
    {
        return Str::uuid()->toString();
    }



}
