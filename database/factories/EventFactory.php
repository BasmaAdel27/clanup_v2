<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $random_days = random_int(1, 9);
    $random_hour = random_int(12, 18);
    return [
        'title' => $faker->realText(90),
        'description' => $faker->realText(1000),
        'starts_at' => now()->addDays($random_days)->hour($random_hour)->minute(0),
        'ends_at' => now()->addDays($random_days)->hour($random_hour + random_int(2,4))->minute(0),
        'is_online' => random_int(0,1),
        'online_meeting_link' => 'https://zoom.us',
        'status' => Event::PUBLISHED,
        'how_to_find_us' => 'null',
    ];
});


$factory->afterCreating(Event::class, function ($event, $faker) {
    $event->setAddress('main', [
        'name' => $faker->company,
        'address_1' => $faker->address,
        'state' => $faker->state,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'country' => $faker->country,
        'lat' => 40.7128,
        'lng' => -74.0060,
    ]);

    // RSVP
    $selected_members = User::inRandomOrder()->limit(20)->pluck('id')->toArray();
    foreach ($selected_members as $user_id) {
        $event->rsvp()->create([
            'event_id' => $event->id,
            'user_id' => $user_id, 
            'response' => random_int(0, 2),
            'guests' => random_int(0, 4)
        ]);
    }
});
