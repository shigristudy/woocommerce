<?php

namespace Corcel\WooCommerce\Tests\Unit\Model;

use Carbon\Carbon;
use Corcel\Model\User;
use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Item;
use Corcel\WooCommerce\Model\Note;
use Corcel\WooCommerce\Model\Order;
use Corcel\WooCommerce\Support\OrderPayment as Payment;
use Corcel\WooCommerce\Tests\TestCase;

class OrderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d H:i:s', '2019-01-01 08:00:00'));
    }

    public function test_its_completed_at_attribute()
    {
        $order = factory(Order::class)->create();
        $order->createMeta('_date_completed', Carbon::now()->timestamp);

        $this->assertInstanceOf(Carbon::class, $order->completed_at);
        $this->assertSame('2019-01-01 08:00:00', $order->completed_at->format('Y-m-d H:i:s'));
    }

    public function test_its_paid_at_attribute()
    {
        $order = factory(Order::class)->create();
        $order->createMeta('_date_paid', Carbon::now()->addHour()->timestamp);

        $this->assertInstanceOf(Carbon::class, $order->paid_at);
        $this->assertSame('2019-01-01 09:00:00', $order->paid_at->format('Y-m-d H:i:s'));
    }

    public function test_it_has_related_author()
    {
        $order = factory(Order::class)->create();
        $user  = factory(User::class)->create();

        $order->author()->associate($user->ID);

        $this->assertTrue($user->is($order->author));
    }

    public function test_it_has_related_items()
    {
        $order = factory(Order::class)->create();
        $order->items()->saveMany(factory(Item::class, 5)->make());

        $this->assertCount(5, $order->items);
        $this->assertInstanceOf(Item::class, $order->items->first());
    }

    public function test_it_has_related_notes()
    {
        $order = factory(Order::class)->create();
        $order->notes()->saveMany(factory(Note::class, 5)->make());

        $this->assertCount(5, $order->notes);
        $this->assertInstanceOf(Note::class, $order->notes->first());
    }

    public function test_it_has_customer()
    {
        $order    = factory(Order::class)->create();
        $customer = factory(Customer::class)->create();

        $order->createMeta([
            '_customer_user' => $customer->id,
        ]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertTrue($order->customer->is($customer));
    }

    public function test_it_has_payment()
    {
        $order = factory(Order::class)->states('paid')->create();

        $this->assertInstanceOf(Payment::class, $order->payment);
        $this->assertNotEmpty($order->payment->title);
    }

    public function test_it_has_status()
    {
        $order = factory(Order::class)->states('completed')->create();

        $this->assertSame('completed', $order->status);
    }

    public function test_it_has_aliases()
    {
        $order = factory(Order::class)->create();

        $this->assertSame($order->ID, $order->id);
        $this->assertSame($order->post_author, $order->author_id);
        $this->assertSame($order->post_parent, $order->parent_id);
        $this->assertSame($order->meta->_order_currency, $order->currency);
        $this->assertSame($order->meta->_customer_user, $order->customer_id);
        $this->assertSame($order->post_excerpt, $order->customer_note);
        $this->assertSame($order->post_date_gmt->getTimestamp(), $order->created_at_gmt->getTimestamp());
        $this->assertSame($order->post_modified_gmt->getTimestamp(), $order->updated_at_gmt->getTimestamp());
    }
}
