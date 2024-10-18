<?php

namespace App\Helpers;

use Carbon\Carbon;

class PdfHelpers
{
    public static function getJapaneseDate(string $date): string
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($date);

        // Extract the year, month, and day
        $currentYear = $carbonDate->year;
        $month = $carbonDate->format('m月');
        $day = $carbonDate->format('d日');

        // Define the start year for each era
        $eras = [
            '令和' => 2019,  // Reiwa started in 2019
            '平成' => 1989,  // Heisei started in 1989
            '昭和' => 1926,  // Showa started in 1926
            '大正' => 1912,  // Taisho started in 1912
            '明治' => 1868,  // Meiji started in 1868
        ];

        // Convert the year to the Japanese era
        foreach ($eras as $era => $startYear) {
            if ($currentYear >= $startYear) {
                $eraYear = $currentYear - $startYear + 1;
                $japaneseYear = $era.($eraYear === 1 ? '元' : $eraYear).'年';

                return $japaneseYear.$month.$day;
            }
        }

        // Return the date in case no era is matched (fallback)
        return $carbonDate->format('Y年m月d日');
    }

    public static function getJapaneseYear(string $year): string
    {
        $currentYear = (int) $year;

        $eras = [
            '令和' => 2019,  // Reiwa started in 2019
            '平成' => 1989,  // Heisei started in 1989
            '昭和' => 1926,  // Showa started in 1926
            '大正' => 1912,  // Taisho started in 1912
            '明治' => 1868,  // Meiji started in 1868
        ];

        foreach ($eras as $era => $startYear) {
            if ($currentYear >= $startYear) {
                $eraYear = $currentYear - $startYear + 1;

                return $era.$eraYear;
            }
        }

        // Fallback in case no era is matched
        return $year;
    }
}
