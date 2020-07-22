<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Vendor;
use Faker\Generator as Faker;
use Faker\Provider\fr_FR\PhoneNumber;
use Faker\Provider\es_VE\Company;

$factory->define(Vendor::class, function (Faker $faker) {
    $faker->addProvider(new PhoneNumber($faker));
    $faker->addProvider(new Company($faker));
    $types = array('sole proprietorship', 'partnership', 'corporation', 'limited liability company');
    return [
        'name' => $faker->unique()->company,
        'contact_no' => $faker->mobileNumber,
        'address' => $faker->address,
        'postal_code' => $faker->postcode,
        'organization_type' => $types[array_rand($types)],
        'user_id' => factory(App\User::class),
        'tin_no' => $faker->taxpayerIdentificationNumber,
        'city' => $faker->city,
        'category_id' => Category::inRandomOrder()->first()->id,
    ];
});
