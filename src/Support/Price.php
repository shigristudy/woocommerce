<?php

namespace Corcel\WooCommerce\Support;

class Price
{
    protected $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public static function make($price)
    {
        return new static($price);
    }

    public function format($decimals = 2, $decimalPointer = '.', $thousandsSeparator = ',')
    {
        return number_format($this->price, $decimals, $decimalPointer, $thousandsSeparator);
    }
}
