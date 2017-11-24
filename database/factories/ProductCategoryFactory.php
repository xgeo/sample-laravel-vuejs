<?php
use Faker\Generator as Faker;
use App\ProductCategory;
$factory->define(ProductCategory::class, function (Faker $faker) {
    return [
        'display' => $faker->name
    ];
});