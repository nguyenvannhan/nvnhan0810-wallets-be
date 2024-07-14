<?php
namespace App\Types;

class TransactionTypes {

    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';

    public static function getTypeList()
    {
        return [
            self::TYPE_INCOME, self::TYPE_EXPENSE,
        ];
    }
}
