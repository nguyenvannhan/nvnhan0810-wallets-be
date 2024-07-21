<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /***** RELATIONS *****/
    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }
}
