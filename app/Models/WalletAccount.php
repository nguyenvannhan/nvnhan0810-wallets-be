<?php

namespace App\Models;

use App\Helpers\AmountHelpers;
use App\Models\Traits\AmountTrait;
use App\Types\WalletAccountTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    use HasFactory, AmountTrait;

    protected $fillable = [
        'type', 'wallet_id', 'balance',
    ];

    /***** RELATIONSHIPS *****/
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    /***** Mutator and Accessor ******/
    public function name(): Attribute {
        return Attribute::make(
            get: fn () => WalletAccountTypes::getList()[$this->type]['name'] ?? '',
        );
    }

    public function balanceCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => AmountHelpers::formatWithCurrency($this->balance),
        );
    }
}
