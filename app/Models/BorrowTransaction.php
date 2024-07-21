<?php

namespace App\Models;

use App\Models\Traits\AmountTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowTransaction extends Model
{
    use HasFactory, AmountTrait;

    protected $fillable = [
        'amount', 'type', 'description', 'transaction_date',
        'wallet_account_id', 'friend_id',
    ];

    /***** RELATIONS *****/
    public function friend()
    {
        return $this->belongsTo(Friend::class);
    }
}
