<?php

namespace App\Models;

use App\Traits\HasAddresses;
use App\Traits\HasTopics;
use App\Traits\Sluggable;
use App\Traits\Visitable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Group extends Model implements HasMedia
{
    use HasAddresses;
    use HasTopics;
    use Sluggable;
    use HasMediaTrait;
    use SoftDeletes;
    use Visitable;

    // Group Types
    const OPEN = 0; // default
    const CLOSED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'describe',
        'created_by',
        'group_type',
        'delete_reason',
    ];

    /**
     * Get route key name for binding routes
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Returns all the owner of this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Returns all the memberships of the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberships()
    {
        return $this->hasMany(GroupMembership::class);
    }

    /**
     * Returns all the events of the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Returns all the discussions of the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    /**
     * Returns all the sponsors of the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sponsors()
    {
        return $this->hasMany(GroupSponsor::class);
    }

    /**
     * Returns all the settings of the group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(GroupSetting::class);
    }

    /**
     * Get specified setting of group
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function getSetting($key)
    {
        return Cache::remember('group_'.$this->id.'_'.$key, 3600, function () use ($key) {
            return GroupSetting::getSetting($key, $this->id);
        });
    }

    /**
     * Set/Update specified setting of group
     *
     * @param string $key
     * @param string $value
     * 
     * @return void
     */
    public function setSetting($key, $value)
    {
        Cache::forget('group_'.$this->id.'_'.$key);
        return GroupSetting::setSetting($key, $value, $this->id);
    }

    /**
     * Returns all the organizers of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizers()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', GroupMembership::ORGANIZER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the co-organizers of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function co_organizers()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', GroupMembership::CO_ORGANIZER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the assistant organizers of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assistant_organizers()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', GroupMembership::ASSISTANT_ORGANIZER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the event organizers of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function event_organizers()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', GroupMembership::EVENT_ORGANIZER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the EVENT_ORGANIZER, ASSISTANT_ORGANIZER, CO_ORGANIZER and ORGANIZER of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function all_organizers()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', '>=', GroupMembership::EVENT_ORGANIZER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the members of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', '>=', GroupMembership::MEMBER)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns all the candidates of this group.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function candidates()
    {
        return $this->belongsToMany(User::class, 'group_memberships')
            ->where('membership', GroupMembership::CANDIDATE)
            ->withTimestamps()
            ->withPivot('membership');
    }

    /**
     * Returns true if the group is open for everyone
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->group_type == $this::OPEN;
    }

    /**
     * Returns true if the group is closed
     * (invite/ask to join only)
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->group_type == $this::CLOSED;
    }

    /**
     * Returns the organizer attribute of this group.
     * 
     * @return \App\Models\User
     */
    public function getOrganizerAttribute()
    {
        return $this->organizers()->first();
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
        $last_media = $this->getMedia('featured_photo')->last();
        return  $last_media ? $last_media->getFullUrl() : $this->getDefaultImage();
    } 

    /**
     * Get member_count attribute
     *
     * @return int
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Scope a query to filter groups by given parameters.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $search
     * @param float                                 $lat
     * @param float                                 $lng
     * @param int                                   $radius
     * @param int                                   $category
     * @param int                                   $topic
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $search, $lat, $lng, $radius, $category, $topic)
    {
        // Filter by search param
        if ($search) $query = $query->search($search);

        // Filter by distance
        if ($lat && $lng) $query = $query->distance($lat, $lng, $radius);

        // Filter by category
        if ($category) {
            $topics_ids = Topic::where('topic_category_id', $category)->pluck('id')->toArray();
            if ($topics_ids && !empty($topics_ids)) $query = $query->withAnyTopics($topics_ids);
        }

        // Filter by topic
        if ($topic) {
            $query = $query->withAllTopics($topic);
        }

        return $query;
    }

    /**
     * Scope query with all the given search param.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $param
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($builder, $param)
    {
        if (!empty($param)) {
            $builder->where('name', 'like', '%'.$param.'%')
                ->orWhere('describe', 'like', '%'.$param.'%');
        }

        return $builder;
    }

    /**
     * Scope a query by search organizer
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchOrganizer($query, $search)
    {
        return $query->whereHas('organizers', function($q) use ($search) {
            $q->search($search);
        });
    }

    /**
     * Scope a query to only include groups in given location and distance
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float                                 $lat
     * @param float                                 $lng
     * @param int                                   $radius
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDistance($query, $lat, $lng, $radius = 5)
    {
        return $query->whereHas('addresses', function($q) use ($lat, $lng, $radius) { 
            $q->isWithinMaxDistance($lat, $lng, $radius);
        });
    }

    /**
     * Scope a query to only include open groups.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('group_type', $this::OPEN);
    }

    /**
     * Scope a query to only include closed groups.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('group_type', $this::CLOSED);
    }
}
