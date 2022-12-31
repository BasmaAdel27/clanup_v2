<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Traits\HasTopics;
use App\Traits\Sluggable;
use App\Traits\UUIDTrait;

class Topic extends Model
{
    use UUIDTrait;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'topic_category_id',
    ];

    /**
     * Define Relation with Topic Category Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic_category()
    {
        return $this->belongsTo(TopicCategory::class)->withDefault();
    }

    /**
     * Get all attached models of the given class to the topic.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries($class): MorphToMany
    {
        return $this->morphedByMany($class, 'topicable', 'topicables', 'topic_id', 'topicable_id', 'id', 'id');
    }

    /**
     * Get first topic(s) by name or create if not exists.
     *
     * @param mixed $topics
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByNameOrCreate($topics): Collection
    {
        return collect(HasTopics::parseDelimitedTopics($topics))->map(function (string $topic) {
            return static::firstByName($topic) ?: static::createByName($topic);
        });
    }

    /**
     * Find topic by name.
     *
     * @param mixed $topics
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByName($topics): Collection
    {
        return collect(HasTopics::parseDelimitedTopics($topics))->map(function (string $topic) {
            return ($exists = static::firstByName($topic)) ? $exists->getKey() : null;
        })->filter()->unique();
    }

    /**
     * Get first topic by name.
     *
     * @param string $topic
     *
     * @return static|null
     */
    public static function firstByName($topic)
    {
        return static::query()->where("name", $topic)->first();
    }

    /**
     * Create topic by name.
     *
     * @param string $topic
     *
     * @return static
     */
    public static function createByName($topic): self
    {
        return static::create([
            'name' => $topic,
            'slug' => Str::slug($topic, '-'),
        ]);
    }
} 
