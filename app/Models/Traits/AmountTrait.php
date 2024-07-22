<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait AmountTrait {

    /***** Mutator and Accessor ******/
    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount) . ' VNĐ',
        );
    }
}
