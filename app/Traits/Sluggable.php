<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * Boot the sluggable trait for a model.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::creating(function (Model $model) {
            $slug = self::generateUniqueSlug($model);
            $model->setSlug($slug);
        });
    }

    /**
     * The name of the column to use for slugs.
     *
     * @return string
     */
    public function getSlugColumnName()
    {
        return 'slug';
    }

    /**
     * Get the string to create a slug from.
     *
     * @return string
     */
    public function getSluggableString()
    {
        return $this->getAttribute('name');
    }

    /**
     * Get the current slug value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getAttribute($this->getSlugColumnName());
    }

    /**
     * Set the slug to the given value.
     *
     * @param  string  $value
     * @return $this
     */
    public function setSlug($value)
    {
        $this->setAttribute($this->getSlugColumnName(), $value);

        return $this;
    }

    /**
     * @param Model $model
     * @return string
     * @throws \Exception
     */
    private static function generateUniqueSlug(Model $model): string
    {
        $slug = empty($model->getSlug()) ? trim(Str::slug($model->getSluggableString())) : $model->getSlug();
        $attribute = trim($model->getSlugColumnName());

        if (empty($slug) || empty($attribute)) {
            throw new \Exception('Incorrect slug attribute or sluggable string for model! Check your "fillable" array.');
        }

        $modelsWithRelatedSlug = $model
            ->withoutGlobalScopes()
            ->where($attribute, 'LIKE', $slug.'%');

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            $modelsWithRelatedSlug = $modelsWithRelatedSlug->withTrashed();
        }
        
        $modelsWithRelatedSlug = $modelsWithRelatedSlug->get([$attribute]);

        $i = 0;
        while ($modelsWithRelatedSlug->contains($attribute, $slug)) {
            ++$i;
            $matches = [];
            if (preg_match('/^(.*?)-(\d+)$/', $slug, $matches)) {
                $nextNum = $matches[2] + $i;
                $slug = "{$matches[1]}-$nextNum";
            } else {
                $slug = "$slug-$i";
            }
        }

        $model = $model
            ->withoutGlobalScopes()
            ->where($attribute, $slug)
            ->first([$attribute]);

        if ($model) {
            // Still not unique...
            $slug = self::generateUniqueSlug($model);
        }

        return $slug;
    }
}