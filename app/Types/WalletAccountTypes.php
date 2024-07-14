<?php
namespace App\Types;

class WalletAccountTypes {
    const KEY_DEFAULT = 'default';

    public static function getList()
    {
        return [
            'default' => [
                'name' => 'TK thanh toán',
            ],
            'debit' => [
                'name' => 'TK ghi nợ',
            ],
            'credit' => [
                'name' => 'TK tín dụng',
            ]
        ];
    }
}
