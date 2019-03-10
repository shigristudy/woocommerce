<?php

namespace Corcel\WooCommerce\Support;

class Currency
{
    public function __construct($currency)
    {
        $this->currency = $this->currencyResolver($currency);
    }

    public static function make($currency)
    {
        return new static($currency);
    }
}
