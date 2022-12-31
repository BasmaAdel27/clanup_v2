<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class GroupSponsor extends Model implements HasMedia
{
    use HasMediaTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'created_by',
        'name',
        'description',
        'website',
    ]; 

    /**
     * Returns the group of this sponsor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
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
     * Get the avatar attribute
     * 
     * @return url
     */
    public function getAvatarAttribute()
    {
        $last_media = $this->getMedia()->last();
        return  $last_media ? $last_media->getFullUrl() : $this->getDefaultImage();
    } 
}