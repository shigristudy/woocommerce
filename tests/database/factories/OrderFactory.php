<?php

use Corcel\WooCommerce\Model\Customer;
use Corcel\WooCommerce\Model\Order;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Order::class, function (Faker $faker) {
    $createdAt = $faker->dateTimeThisYear;

    return [
        'post_author'           => $faker->randomNumber,
        'post_date'             => $createdAt->format('Y-m-d H:i:s'),
        'post_date_gmt'         => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
        'post_content'          => '',
        'post_title'            => $title = sprintf('Order &ndash; %s', $createdAt->format('F d, Y @ H:i A')),
        'post_excerpt'          => $faker->optional(30, '')->text(100),
        'post_status'           => 'wc-pending',
        'comment_status'        => 'closed',
        'ping_status'           => 'closed',
        'post_password'         => sprintf('wc_order_%s', $faker->bothify('??????????????')),
        'post_name'             => Str::slug($title),
        'to_ping'               => '',
        'pinged'                => '',
        'post_modified'         => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'post_modified_gmt'     => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'post_content_filtered' => '',
        'post_parent'           => 0,
        'guid'                  => 'http://example.com/?post_type=shop_order&#038;p=' . $faker->numberBetween(1, 100),
        'menu_order'            => 0,
        'post_type'             => 'shop_order',
        'post_mime_type'        => '',
        'comment_count'         => 0,
    ];
});

$factory->afterCreating(Order::class, function (Order $order, Faker $faker) {
    $order->createMeta([
        '_order_key'            => sprintf('wc_order_%s', $faker->bothify('??????????????')),
        '_payment_method_title' => $paymentMethod = ucwords($faker->words($faker->numberBetween(1, 3), true)),
        '_payment_method'       => Str::slug($paymentMethod),
        '_transaction_id'       => '',
        '_customer_ip_address'  => $faker->ipv4,
        '_customer_user_agent'  => $faker->userAgent,
        '_created_via'          => 'checkout',
        '_cart_hash'            => md5($faker->bothify('??????')),
        '_order_currency'       => $faker->currencyCode,
    ]);

    $address = function ($type) use ($faker) {
        return [
            "_{$type}_first_name" => $faker->firstName,
            "_{$type}_last_name"  => $faker->lastName,
            "_{$type}_company"    => $faker->optional(30, '')->company,
            "_{$type}_address_1"  => $faker->streetAddress,
            "_{$type}_address_2"  => $faker->optional(30, '')->secondaryAddress,
            "_{$type}_city"       => $faker->city,
            "_{$type}_state"      => $faker->optional(30, '')->state,
            "_{$type}_postcode"   => $faker->postcode,
            "_{$type}_country"    => $faker->country,
        ];
    };

    $order->createMeta($address('billing'));
    $order->createMeta([
        'billing_email' => $faker->email,
        'billing_phone' => $faker->phoneNumber,
    ]);

    $order->createMeta($address('shipping'));
});

$factory->state(Order::class, 'paid', function (Faker $faker) {
    return [];
});

$factory->afterCreatingState(Order::class, 'paid', function (Order $order, Faker $faker) {
    $paidAt = $faker->dateTimeThisYear();

    $order->createMeta([
        '_date_paid' => $paidAt->getTimestamp(),
        '_paid_date' => $paidAt->format('Y-m-d H:i:s'),
    ]);
});

$factory->state(Order::class, 'completed', function (Faker $faker) {
    return [
        'post_status' => 'wc-completed',
    ];
});

$factory->afterCreatingState(Order::class, 'completed', function (Order $order, Faker $faker) {
    $completedAt = $faker->dateTimeThisYear();

    $order->createMeta([
        '_date_completed' => $completedAt->getTimestamp(),
        '_completed_date' => $completedAt->format('Y-m-d H:i:s'),
    ]);
});
