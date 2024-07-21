<?php
namespace App\Types;

class BorrowTransactionTypes
{
    const TYPE_BORROW = 'borrow';
    const TYPE_LEND = 'lend';

    public static function getTypeList() {
        return [
            self::TYPE_BORROW => 'Mượn',
            self::TYPE_LEND => 'Cho mượn',
        ];
    }
}
