<?php

use App\Report;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Report::class, function (Faker $faker) {
    return [
        'title' => ucfirst($faker->words(mt_rand(4, 9), true)),
        'details' => $faker->paragraphs(mt_rand(2, 5), true),
        'lat' => $faker->latitude,
        'lon' => $faker->longitude,
        'data' => [],
    ];
});
