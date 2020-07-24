<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DealItem;
use Faker\Generator as Faker;

$factory->define(DealItem::class, function (Faker $faker) {
    $original_price = $faker->numberBetween(700,1000);
    $offered_price = $original_price - ($original_price*(rand(50,90)/100));
    return [
        'item_name' => $faker->name,
        'item_description' => $faker->paragraph(3),
        'quantity' => $faker->numberBetween(10,100),
        'original_price' => $original_price,
        'offered_price' => $offered_price,
    ];
});
