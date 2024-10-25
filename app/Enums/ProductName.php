<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductName extends Enum
{
    const ALL = '全て';

    const ALTERVOICE = 'オルタボイス';

    const EDUCATIONAL_COUNSELING = '教育相談';

    const PEOPLE_IN_NEED = '生活困窮者';

    public static function getValueByLowerCaseKey(string $lowerKey): ?string
    {
        foreach (self::getKeys() as $key) {
            if (strtolower($key) === $lowerKey) {
                return self::getValue($key);
            }
        }

        return null;
    }
}
