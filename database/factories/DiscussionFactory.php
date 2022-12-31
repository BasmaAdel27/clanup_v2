<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Discussion;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Discussion::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(140),
        'content' =>  $faker->realText(300),
    ];
});

$factory->afterCreating(Discussion::class, function ($discussion, $faker) {
    // Comments
    $selected_members = User::inRandomOrder()->limit(10)->pluck('id')->toArray();
    foreach ($selected_members as $user_id) {
        $discussion->comments()->create([
            'user_id' => $user_id,
            'content' => $faker->realText(150),
        ]);
    }
});