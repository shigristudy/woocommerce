<?php

use Corcel\WooCommerce\Model\Customer;
use Faker\Generator;

$factory->define(Customer::class, function (Generator $faker) {
    return [
        'user_login'          => 'customer',
        'user_pass'           => 'secret',
        'user_nicename'       => 'customer',
        'user_email'          => 'customer@example.com',
        'user_url'            => 'http://customer.example.com',
        'user_registered'     => $faker->dateTime,
        'user_activation_key' => str_random(10),
        'user_status'         => 0,
        'display_name'        => $faker->name,
    ];
});

$factory->afterCreating(Customer::class, function (Customer $customer, Generator $faker) {
    $address = function ($type) use ($faker) {
        return [
            "{$type}_first_name" => $faker->firstName,
            "{$type}_last_name"  => $faker->lastName,
            "{$type}_company"    => $faker->optional(30, '')->company,
            "{$type}_address_1"  => $faker->streetAddress,
            "{$type}_address_2"  => $faker->optional(30, '')->secondaryAddress,
            "{$type}_city"       => $faker->city,
            "{$type}_state"      => $faker->optional(30, '')->state,
            "{$type}_postcode"   => $faker->postcode,
            "{$type}_country"    => $faker->country,
        ];
    };

    $customer->createMeta($address('billing'));
    $customer->createMeta([
        'billing_email' => $faker->email,
        'billing_phone' => $faker->phoneNumber,
    ]);

    $customer->createMeta($address('shipping'));
});
