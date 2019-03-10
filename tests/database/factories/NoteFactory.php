<?php

use Corcel\WooCommerce\Model\Note;

$factory->define(Note::class, function (Faker\Generator $faker) {
    return [
        'comment_author'       => $faker->name,
        'comment_author_email' => $faker->email,
        'comment_author_url'   => $faker->url,
        'comment_author_IP'    => $faker->ipv4,
        'comment_date'         => $faker->dateTime,
        'comment_date_gmt'     => $faker->dateTime,
        'comment_content'      => $faker->sentence(),
        'comment_karma'        => 0,
        'comment_approved'     => 1,
        'comment_agent'        => '',
        'comment_type'         => 'order_note',
        'comment_parent'       => 0,
        'user_id'              => 0,
    ];
});
