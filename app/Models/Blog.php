<?php

namespace App\Models;

use App\Traits\Sluggable;
use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Blog extends Model implements HasMedia
{
    use HasMediaTrait;
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
        'blog_category_id',
        'description',
        'content',
        'is_active',
        'order',
        'created_by_id',
        'updated_by_id',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
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
     * Define Relation with Blog Category Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class)->withDefault();
    }

    /**
     * Define Relation with Created By User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Define Relation with Updated By User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
 
    /**
     * Return Default Image Url
     * 
     * @return url
     */
    public function getDefaultImage()
    {
        if (config('app.is_demo') || config('app.envato_review')) {
            return 'https://source.unsplash.com/random/300x200?sig='.rand(1, 100000);
        }
        return asset('assets/images/image_placeholder.jpeg');
    }

    /**
     * Get the image attribute
     * 
     * @return url
     */
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl() ? $this->getFirstMediaUrl() : $this->getDefaultImage();
    } 

    /**
     * Scope a query to include published blogs.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($builder)
    {
        return $builder->where('is_active', 1);
    }

    /**
     * Scope a query to include blogs of given blog category by slug.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByBlogCategorySlug($builder, $slug)
    {
        return $builder->whereHas('blog_category', function($query) use ($slug) { 
            $query->where('slug', $slug);
        });
    }
}
