<?php

namespace Corcel\WooCommerce\Support;

use Carbon\Carbon;
use DateTime as BaseDateTime;
use Illuminate\Contracts\Support\Arrayable;

class DateTime implements Arrayable
{
    protected $datetime;

    public function __construct($datetime)
    {
        $this->datetime = $this->parse($datetime);
    }

    public static function make($datetime)
    {
        return new static($datetime);
    }

    public function parse($datetime)
    {
        if (empty($datetime)) {
            return;
        }

        if ($datetime instanceof BaseDateTime) {
            return Carbon::instance($datetime);
        }

        if (preg_match('/^\d+$/', $datetime)) {
            return Carbon::createFromTimestamp($datetime);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $datetime)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $datetime)) {
            return Carbon::createFromFormat('Y-m-d', $datetime);
        }

        try {
            return Carbon::parse($datetime);
        } catch (Exception $e) {
            //
        }

        return;
    }

    public function get()
    {
        return $this->datetime;
    }

    public function set($datetime)
    {
        $this->datetime = $this->parse($datetime);

        return $this;
    }

    public function format($format = 'Y-m-d H:i:s')
    {
        if ($this->datetime instanceof Carbon) {
            return $this->datetime->format($format);
        }
    }

    public function toArray()
    {
        return $this->datetime;
    }
}
