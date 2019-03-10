<?php

use Corcel\Model\Taxonomy;

$factory->state(Taxonomy::class, 'product_category', function (Faker\Generator $faker) {
    return [
        'taxonomy' => 'product_cat',
    ];
});
