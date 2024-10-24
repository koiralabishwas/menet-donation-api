<?php

namespace App\Enums;

enum StripeProducts: string
{
    case ALTERVOICE = 'prod_Q2GxSqbpfzdba4';
    case EDUCATIONAL_COUNSELING = 'prod_1';
    case PEOPLE_IN_NEED = 'prod_2';
    case ALL = 'prod_3';

    /**
     * 全値キー取得
     */
    public static function getAllKeys(): array
    {
        return array_map(fn ($case) => strtolower($case->name), self::cases());
    }

    /**
     * 小文字のキーで値を取得できるようにする
     */
    public static function getByKey(string $key): ?self
    {
        return self::tryFrom(strtolower($key));
    }
}
