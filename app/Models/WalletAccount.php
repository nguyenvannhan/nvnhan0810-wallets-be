<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    use HasFactory;

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
}
