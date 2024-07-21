<?php

namespace App\Models\Traits;

trait AmountTrait {

    /***** Mutator and Accessor ******/
    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount) . ' VND',
        );
    }
}
