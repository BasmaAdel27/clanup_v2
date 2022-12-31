<?php

namespace App\Models;

use App\Traits\Sluggable;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Sluggable;
    use Visitable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'content',
        'is_active',
        'is_deletable',
        'show_on_footer',
        'order',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'is_deletable' => 'boolean',
        'show_on_footer' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get route key name for binding routes
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Find by slug
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Scope a query to include published pages.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($builder)
    {
        return $builder->where('is_active', 1);
    }
}
