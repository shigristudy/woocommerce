<?php

namespace Corcel\WooCommerce\Support;

use Illuminate\Contracts\Support\Arrayable;

class OrderStatus implements Arrayable
{
    protected $status;

    protected $validStatuses = [
        'cancelled',
        'completed',
        'failed',
        'on-hold',
        'pending',
        'processing',
        'refunded',
    ];

    public function __construct($status)
    {
        $this->status = $status;
    }

    public static function make($status)
    {
        return new static($status);
    }

    public function format()
    {
        $status = 'wc-' === substr($this->status, 0, 3) ? substr($this->status, 3) : $this->status;

        if (in_array($status, $this->validStatuses, true)) {
            return $status;
        }
    }

    public function formatPrefixed()
    {
        $status = $this->format();

        if (!empty($status)) {
            return "wc-{$status}";
        }
    }

    public function __toString()
    {
        return $this->format() ?: '';
    }

    public function toArray()
    {
        return $this->format();
    }
}
