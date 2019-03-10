<?php

namespace Corcel\WooCommerce\Model\Builder;

use Corcel\Model\Builder\PostBuilder;
use Corcel\WooCommerce\Support\OrderStatus as Status;

class OrderBuilder extends PostBuilder
{
    /**
     * @return $this
     */
    public function cancelled()
    {
        return $this->status('cancelled');
    }

    /**
     * @return $this
     */
    public function completed()
    {
        return $this->status('completed');
    }

    /**
     * @return $this
     */
    public function failed()
    {
        return $this->status('failed');
    }

    /**
     * @return $this
     */
    public function onHold()
    {
        return $this->status('on-hold');
    }

    /**
     * @return $this
     */
    public function pending()
    {
        return $this->status('pending');
    }

    /**
     * @return $this
     */
    public function processing()
    {
        return $this->status('processing');
    }

    /**
     * @return $this
     */
    public function refunded()
    {
        return $this->status('refunded');
    }

    /**
     * @param $this
     */
    public function status($status)
    {
        $wooStatus = Status::make($status)->formatPrefixed();

        if (!empty($wooStatus)) {
            return parent::status($wooStatus);
        }

        return parent::status($status);
    }
}
