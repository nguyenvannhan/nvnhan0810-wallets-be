<?php
namespace App\Types;

class WalletAccountTypes {
    const KEY_DEFAULT = 'default';

    public static function getList()
    {
        return [
            'default' => [
                'name' => 'Tài khoản Thanh Toán',
            ]
        ];
    }
}
