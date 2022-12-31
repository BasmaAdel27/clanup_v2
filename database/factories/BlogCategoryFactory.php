<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog;
use App\Models\BlogCategory;
use Faker\Generator as Faker;

$factory->define(BlogCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});


$factory->afterCreating(BlogCategory::class, function ($blog_category, $faker) {
    Blog::create([
        'name' => $faker->realText(100),
        'blog_category_id' => $blog_category->id,
        'description' => $faker->realText(120),
        'content' => $faker->realText(3000),
        'is_active' => 1,
        'order' => 0,
    ]);
});