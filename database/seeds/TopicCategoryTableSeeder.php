<?php

use Illuminate\Database\Seeder;
use App\Models\TopicCategory;

class TopicCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topic_categories = [
            [
                'id' => 1,
                'name' => 'Arts & Culture',
                'slug' => 'arts-Culture',
            ],
            [
                'id' => 2,
                'name' => 'Book Clubs',
                'slug' => 'book-clubs',
            ],
            [
                'id' => 3,
                'name' => 'Career & Business',
                'slug' => 'career-business',
            ],
            [
                'id' => 4,
                'name' => 'Cars & Motorcycles',
                'slug' => 'cars-motorcycles',
            ],
            [
                'id' => 5,
                'name' => 'Community & Environment',
                'slug' => 'community-environment',
            ],
            [
                'id' => 6,
                'name' => 'Dancing',
                'slug' => 'dancing',
            ],
            [
                'id' => 7,
                'name' => 'Education & Learning',
                'slug' => 'education-learning',
            ],
            [
                'id' => 8,
                'name' => 'Fashion & Beauty',
                'slug' => 'fashion-beauty',
            ],
            [
                'id' => 9,
                'name' => 'Fitness',
                'slug' => 'fitness',
            ],
            [
                'id' => 10,
                'name' => 'Food & Drink',
                'slug' => 'food-drink',
            ],
            [
                'id' => 11,
                'name' => 'Games',
                'slug' => 'games',
            ],
            [
                'id' => 12,
                'name' => 'Health & Wellbeing',
                'slug' => 'health-wellbeing',
            ],
            [
                'id' => 13,
                'name' => 'Hobbies & Crafts',
                'slug' => 'hobbies-crafts',
            ],
            [
                'id' => 14,
                'name' => 'LGBT',
                'slug' => 'LGBT',
            ],
            [
                'id' => 15,
                'name' => 'Language & Ethnic Identity',
                'slug' => 'language-ethnic-identity',
            ],
            [
                'id' => 16,
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
            ],
            [
                'id' => 17,
                'name' => 'Movements & Politics',
                'slug' => 'movements-politics',
            ],
            [
                'id' => 18,
                'name' => 'Movies & Film',
                'slug' => 'movies-film',
            ],
            [
                'id' => 19,
                'name' => 'Music',
                'slug' => 'music',
            ],
            [
                'id' => 20,
                'name' => 'New Age & Spirituality',
                'slug' => 'new-age-spirituality',
            ],
            [
                'id' => 21,
                'name' => 'Outdoors & Adventure',
                'slug' => 'outdoors-adventure',
            ],
            [
                'id' => 22,
                'name' => 'Paranormal',
                'slug' => 'paranormal',
            ],
            [
                'id' => 23,
                'name' => 'Parents & Family',
                'slug' => 'parents-family',
            ],
            [
                'id' => 24,
                'name' => 'Pets & Animals',
                'slug' => 'pets-animals',
            ],
            [
                'id' => 25,
                'name' => 'Photography',
                'slug' => 'photography',
            ],
            [
                'id' => 26,
                'name' => 'Religion & Beliefs',
                'slug' => 'religion-beliefs',
            ],
            [
                'id' => 27,
                'name' => 'Sci-Fi & Fantasy',
                'slug' => 'sci-fi-fantasy',
            ],
            [
                'id' => 28,
                'name' => 'Singles',
                'slug' => 'singles',
            ],
            [
                'id' => 29,
                'name' => 'Socializing',
                'slug' => 'socializing',
            ],
            [
                'id' => 30,
                'name' => 'Sports & Recreation',
                'slug' => 'sports-recreation',
            ],
            [
                'id' => 31,
                'name' => 'Support',
                'slug' => 'support',
            ],
            [
                'id' => 32,
                'name' => 'Tech',
                'slug' => 'tech',
            ],
            [
                'id' => 33,
                'name' => 'Writing',
                'slug' => 'writing',
            ],
        ];

        foreach ($topic_categories as $topic_category) {
            $topic = new TopicCategory();
            $topic->id = $topic_category['id'];
            $topic->name = $topic_category['name'];
            $topic->slug = $topic_category['slug'];
            $topic->save();
        }

    }
}
