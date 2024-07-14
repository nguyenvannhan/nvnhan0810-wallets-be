<?php

namespace App\Models;

use App\Types\TransactionTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_account_id',
        'type', 'amount', 'description',
    ];

    /***** RELATIONSHIPS *****/
    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }

    /***** Mutator and Accessor ******/
    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount) . ' VND',
        );
    }

    public function isIncome(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === TransactionTypes::TYPE_INCOME,
        );
    }
}
