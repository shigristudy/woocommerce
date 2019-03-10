<?php

use Corcel\Model\Taxonomy;

$factory->state(Taxonomy::class, 'product_attribute', function (Faker\Generator $faker) {
    return [
        'taxonomy' => sprintf('pa_%s', $faker->word),
    ];
});
