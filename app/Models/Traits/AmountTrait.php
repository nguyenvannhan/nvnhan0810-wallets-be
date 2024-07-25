<?php

namespace App\Models\Traits;

use App\Helpers\AmountHelpers;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait AmountTrait
{
    /***** Mutator and Accessor ******/
    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => AmountHelpers::formatWithCurrency($this->amount),
        );
    }
}
