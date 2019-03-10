<?php

namespace Corcel\WooCommerce\Support;

use Corcel\WooCommerce\Model\Product;
use Corcel\WooCommerce\WooCommerce;
use Illuminate\Contracts\Support\Htmlable;

class ProductPrice implements Htmlable
{
    protected $product;

    protected $currency;

    public function __construct(Product $product)
    {
        $this->product  = $product;
        $this->currency = WooCommerce::currency();
    }

    public function toHtml()
    {
        switch ($this->product->type) {
            case 'grouped':
                return $this->groupedPrice();
            case 'variable':
                return $this->variablePrice();
            case 'simple':
            case 'external':
            default:
                return $this->simplePrice();
        }
    }

    public function __toString()
    {
        return $this->toHtml();
    }

    public function simplePrice()
    {
        if ($this->product->on_sale) {
            return sprintf(
                '<s>%1$s&nbsp;%3$s</s> %2$s&nbsp;%3$s',
                $this->format($this->product->regular_price),
                $this->format($this->product->sale_price),
                $this->currency
            );
        }

        return sprintf('%1$s&nbsp;%2$s', $this->format($this->product->price), $this->currency);
    }

    public function groupedPrice()
    {
        $prices = $this->product->children->pluck('price')->toArray();

        return $this->range($prices);
    }

    public function variablePrice()
    {
        $prices = $this->product->variations->pluck('price')->toArray();

        return $this->range($prices);
    }

    protected function range(array $prices)
    {
        $minimum  = $this->format(min($prices));
        $maxiumum = $this->format(max($prices));

        if ($minimum != $maxiumum) {
            return sprintf('%1$s&nbsp;%3$s &ndash; %2$s&nbsp;%3$s', $minimum, $maxiumum, $this->currency);
        }

        return sprintf('%1$s&nbsp;%2$s', $minimum, $this->currency);
    }

    protected function format($price)
    {
        return number_format($price, 2);
    }
}
