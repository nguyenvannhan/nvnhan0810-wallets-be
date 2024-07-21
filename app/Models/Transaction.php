<?php

namespace App\Models;

use App\Models\Traits\AmountTrait;
use App\Types\TransactionTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, AmountTrait;

    protected $fillable = [
        'wallet_account_id',
        'type', 'amount', 'description',
    ];

    /***** RELATIONSHIPS *****/
    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }

    public function transactionAttributes()
    {
        return $this->hasMany(TransactionAttribute::class);
    }

    /***** Mutator and Accessor ******/
    public function isIncome(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === TransactionTypes::TYPE_INCOME,
        );
    }

    public function scopeFilter(Builder $query, array $data)
    {
        if (!empty($data['start_date'])) {
            $query->whereDate('created_at', '>=', Carbon::parse($data['start_date'])->endOfDay()->format("Y-m-d H:i:s"));
        }

        if (!empty($data['end_date'])) {
            $query->whereDate('created_at', '<=', Carbon::parse($data['end_date'])->endOfDay()->format("Y-m-d H:i:s"));
        }

        if (!empty($data['wallet_id'])) {
            $query->whereHas('walletAccount.wallet', function (Builder $walletQuery) use ($data) {
                $walletQuery->where('wallets.id', $data['wallet_id']);
            });
        }
    }
}
