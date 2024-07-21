<?php
namespace App\Types;

class WalletAccountTypes {
    const KEY_DEFAULT = 'default';

    const TYPE_CREDIT = 'credit';

    const TYPE_DEBIT = 'debit';

    public static function getList()
    {
        return [
            'default' => [
                'name' => 'TK thanh toán',
            ],
            self::TYPE_DEBIT => [
                'name' => 'TK ghi nợ',
            ],
            self::TYPE_CREDIT => [
                'name' => 'TK tín dụng',
            ]
        ];
    }

    public static function getNotIncomeTypeList()
    {
        return [
            self::TYPE_CREDIT, self::TYPE_DEBIT
        ];
    }
}
