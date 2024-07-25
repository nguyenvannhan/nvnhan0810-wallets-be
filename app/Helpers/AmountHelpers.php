<?php
namespace App\Helpers;

class AmountHelpers {
    public static function formatWithCurrency(int $amount)
    {
        return number_format($amount) . ' VNĐ';
    }
}
