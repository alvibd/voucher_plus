<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Deal;
use Faker\Generator as Faker;

$factory->define(Deal::class, function (Faker $faker) {
    return [
        'campaign_name' => $faker->name,
        'campaign_description' => $faker->paragraph,
        'terms_and_conditions' => $faker->paragraph,
        'launching_date' => today()->toDateString(),
        'expiration_date' => today()->addDays(rand(1,14))->toDateString(),
        'final_redemption_date' => today()->addDays(rand(15,21))->toDateString()
    ];
});
