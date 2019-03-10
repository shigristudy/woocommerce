<?php

use Corcel\WooCommerce\Model\Item;

$factory->define(Item::class, function (Faker\Generator $faker) {
    return [
        'order_item_name' => $faker->title,
        'order_item_type' => $faker->randomElement(['line_item']),
    ];
});
