<?php

namespace App\Traits;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTopics
{
    /**
     * The topics delimiter.
     *
     * @var string
     */
    protected static $topicsDelimiter = ',';

    /**
     * Get all attached topics to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function topics(): MorphToMany
    {
        return $this->morphToMany(Topic::class, 'topicable', 'topicables', 'topicable_id', 'topic_id')->withTimestamps();
    }

    /**
     * Get topics delimiter.
     *
     * @return string
     */
    public static function getTopicsDelimiter()
    {
        return static::$topicsDelimiter;
    }

    /**
     * Set topics delimiter.
     *
     * @param string $delimiter
     *
     * @return void
     */
    public static function setTopicsDelimiter($delimiter)
    {
        static::$topicsDelimiter = $delimiter;
    }

    /**
     * Boot the HasTopics trait for the model.
     *
     * @return void
     */
    public static function bootHasTopics()
    {
        static::deleted(function (self $model) {
            $model->topics()->detach();
        });
    }

    /**
     * Attach the given topic(s) to the model.
     *
     * @param mixed $topics
     *
     * @return void
     */
    public function setTopicsAttribute($topics): void
    {
        static::saved(function (self $model) use ($topics) {
            $model->syncTopics($topics);
        });
    }

    /**
     * Scope query with all the given topics.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $topics
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllTopics(Builder $builder, $topics): Builder
    {
        $topics = $this->parseTopics($topics);

        collect($topics)->each(function ($topic) use ($builder) {
            $builder->whereHas('topics', function (Builder $builder) use ($topic) {
                return $builder->where('id', $topic);
            });
        });

        return $builder;
    }

    /**
     * Scope query with any of the given topics.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $topics
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyTopics(Builder $builder, $topics): Builder
    {
        $topics = $this->parseTopics($topics);

        return $builder->whereHas('topics', function (Builder $builder) use ($topics) {
            $builder->whereIn('id', $topics);
        });
    }

    /**
     * Scope query without any of the given topics.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $topics
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutTopics(Builder $builder, $topics): Builder
    {
        $topics = $this->parseTopics($topics);

        return $builder->whereDoesntHave('topics', function (Builder $builder) use ($topics) {
            $builder->whereIn('id', $topics);
        });
    }

    /**
     * Scope query without any topics.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyTopics(Builder $builder): Builder
    {
        return $builder->doesntHave('topics');
    }

    /**
     * Determine if the model has any of the given topics.
     *
     * @param mixed  $topics
     *
     * @return bool
     */
    public function hasAnyTopics($topics): bool
    {
        $topics = $this->parseTopics($topics);

        return ! $this->topics->pluck('id')->intersect($topics)->isEmpty();
    }

    /**
     * Determine if the model has all of the given topics.
     *
     * @param mixed  $topics
     *
     * @return bool
     */
    public function hasAllTopics($topics): bool
    {
        $topics = $this->parseTopics($topics);

        return collect($topics)->diff($this->topics->pluck('id'))->isEmpty();
    }

    /**
     * Parse delimited topics.
     *
     * @param mixed $topics
     *
     * @return array
     */
    public static function parseDelimitedTopics($topics): array
    {
        if (is_string($topics) && mb_strpos($topics, static::$topicsDelimiter) !== false) {
            $delimiter = preg_quote(static::$topicsDelimiter, '#');
            $topics = array_map('trim', preg_split("#[{$delimiter}]#", $topics, -1, PREG_SPLIT_NO_EMPTY));
        }

        return array_unique(array_filter((array) $topics));
    }

    /**
     * Attach model topics.
     *
     * @param mixed $topics
     *
     * @return $this
     */
    public function attachTopics($topics)
    {
        // Use 'sync' not 'attach' to avoid Integrity constraint violation
        $this->topics()->sync($this->parseTopics($topics), false);

        return $this;
    }

    /**
     * Sync model topics.
     *
     * @param mixed $topics
     * @param bool  $detaching
     *
     * @return $this
     */
    public function syncTopics($topics, bool $detaching = true)
    {
        $this->topics()->sync($this->parseTopics($topics, null, null, true), $detaching);

        return $this;
    }

    /**
     * Detach model topics.
     *
     * @param mixed $topics
     *
     * @return $this
     */
    public function detachTopics($topics = null)
    {
        ! $topics || $topics = $this->parseTopics($topics);

        $this->topics()->detach($topics);

        return $this;
    }

    /**
     * Parse topics.
     *
     * @param mixed  $rawTopics
     * @param bool   $create
     *
     * @return array
     */
    protected function parseTopics($rawTopics, $create = false): array
    {
        (is_iterable($rawTopics) || is_null($rawTopics)) || $rawTopics = [$rawTopics];

        [$strings, $topics] = collect($rawTopics)->map(function ($topic) {
            ! is_numeric($topic) || $topic = (int) $topic;

            ! $topic instanceof Model || $topic = [$topic->getKey()];
            ! $topic instanceof Collection || $topic = $topic->modelKeys();
            ! $topic instanceof BaseCollection || $topic = $topic->toArray();

            return $topic;
        })->partition(function ($item) {
            return is_string($item);
        });

        return $topics->merge(Topic::{$create ? 'findByNameOrCreate' : 'findByName'}($strings->toArray())->pluck('id'))->toArray();
    }
}