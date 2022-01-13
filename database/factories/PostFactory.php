<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
       'user_id' => User::all()->random()->id,
       'title' => $faker->word(),
       'post' => $faker->paragraph(),

    ];
});
