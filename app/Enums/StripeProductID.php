<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class StripeProductID extends Enum
{
    const ALTERVOICE = 'prod_Q2GxSqbpfzdba4';

    const EDUCATIONAL_COUNSELING = 'prod_1';

    const PEOPLE_IN_NEED = 'prod_2';

    const ALL = 'prod_3';

    public static function getLowerCaseKeys(): array
    {
        return array_map('strtolower', self::getKeys());
    }

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
