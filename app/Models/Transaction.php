<?php

namespace App\Models;

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
}
