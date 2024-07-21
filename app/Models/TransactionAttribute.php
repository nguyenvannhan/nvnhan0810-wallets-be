<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'key', 'value',
    ];

    /***** RELATIONS *****/
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
