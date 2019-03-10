<?php

namespace Corcel\WooCommerce\Traits;

use Corcel\WooCommerce\Support\Address;

trait HasAddresses
{
    /**
     * Get the model's billing address instance.
     *
     * @return  \Corcel\WooCommerce\Support\Address
     */
    public function getBillingAddressAttribute()
    {
        return new Address($this, $this->meta, Address::BILLING);
    }

    /**
     * Get the model's shipping address instance.
     *
     * @return  \Corcel\WooCommerce\Support\Address
     */
    public function getShippingAddressAttribute()
    {
        return new Address($this, $this->meta, Address::SHIPPING);
    }
}
