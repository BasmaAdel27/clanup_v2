<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Topic;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'describe' => clean($faker->realText(1000)),
        'created_by' => factory(\App\Models\User::class),
        'group_type' => Group::OPEN,
    ];
});


$factory->afterCreating(Group::class, function ($group, $faker) {
    $group->memberships()->create([
        'user_id' => $group->created_by, 
        'membership' => GroupMembership::ORGANIZER,
    ]);

    $group->setAddress('main', [
        'name' => $faker->company,
        'address_1' => $faker->address,
        'state' => $faker->state,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'country' => $faker->country,
        'lat' => 40.7128,
        'lng' => -74.0060,
    ]);

    // Members
    $selected_members = User::inRandomOrder()->limit(20)->pluck('id')->toArray();
    foreach ($selected_members as $user_id) {
        $group->memberships()->create([
            'user_id' => $user_id, 
            'membership' => GroupMembership::MEMBER,
        ]);
    }

    // Attach topics
    $selected_topics = Topic::inRandomOrder()->limit(5)->pluck('id')->toArray();
    $group->attachTopics($selected_topics);

    // Discussions 
    $discussions = factory(\App\Models\Discussion::class, 10)->create([
        'user_id' => $selected_members[random_int(0, count($selected_members) - 1)],
        'group_id' => $group->id,
    ]);

    // Events 
    $events = factory(\App\Models\Event::class, 10)->create([
        'created_by' => $group->created_by,
        'group_id' => $group->id,
    ]);
});