<?php

use Faker\Generator as Faker;
use Sarala\Dummy\Tag;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
