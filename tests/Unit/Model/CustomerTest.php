<?php

namespace Corcel\WooCommerce\Tests\Unit\Model;

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_it_has_related_orders()
    {
        $customer = factory(Customer::class)->create();

        factory(Order::class, 5)->create()->each(function ($order) use ($customer) {
            $order->createMeta([
                '_customer_user' => $customer->id,
            ]);
        });

        $this->assertCount(5, $customer->orders);
        $this->assertInstanceOf(Order::class, $customer->orders->first());
    }
}
