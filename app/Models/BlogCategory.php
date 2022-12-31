<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use Sluggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get route key name for binding routes
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Define Relation with Blog Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
} 
