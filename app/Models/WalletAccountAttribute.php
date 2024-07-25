<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccountAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_account_id', 'key', 'value',
    ];

    /***** RELATIONSHIPS *****/
    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }
}
