<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Libros;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Libros::class, function (Faker $faker) {
    return [
        'titulo' => $faker->sentence(3),
        'numPaginas' => $faker->numberBetween(50, 600),
        'sinopsis' => $faker->text(150)
    ];
});
