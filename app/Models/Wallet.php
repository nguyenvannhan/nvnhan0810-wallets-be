<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /***** RELATIONSHIPS *****/
    public function walletAccounts()
    {
        return $this->hasMany(WalletAccount::class);
    }
}
