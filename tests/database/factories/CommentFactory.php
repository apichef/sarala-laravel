<?php

use Faker\Generator as Faker;
use Sarala\Dummy\Comment;
use Sarala\Dummy\Post;
use Sarala\Dummy\User;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraphs(1, true),
        'created_at' => $faker->dateTimeBetween('-2 months'),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'post_id' => function () {
            return factory(Post::class)->create()->id;
        },
    ];
});
