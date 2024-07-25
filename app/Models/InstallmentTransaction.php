<?php

namespace App\Models;

use App\Helpers\AmountHelpers;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'wallet_account_id',
        'monthly_amount', 'start_paid_date', 'next_paid_date',
        'remain_months', 'total_months',
    ];

    /***** Mutator and Accessor *****/
    public function monthlyAmountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => AmountHelpers::formatWithCurrency($this->monthly_amount),
        );
    }
}
