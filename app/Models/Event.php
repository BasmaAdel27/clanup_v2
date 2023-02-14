<?php

namespace App\Models;

use App\Traits\HasAddresses;
use App\Traits\HasTopics;
use App\Traits\Sluggable;
use App\Traits\UUIDTrait;
use App\Traits\Visitable;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\Event\Reminder;
use App\Notifications\Event\Announcement;
use App\Services\Notification\Notification;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\PropertyTypes\TextPropertyType;

class Event extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasAddresses;
    use UUIDTrait;
    use HasTopics;
    use Sluggable;
    use SoftDeletes;
    use Visitable;

    // Event Types
    const DRAFT = 0; // default
    const PUBLISHED = 1;

    // Event Fee Method
    const FEE_METHOD_CASH = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'created_by',
        'title',
        'slug',
        'description',
        'starts_at',
        'ends_at',
        'is_online',
        'online_meeting_link',
        'rsvp_starts_at',
        'rsvp_ends_at',
        'attendee_limit',
        'status',
        'announced_at',
        'how_to_find_us',
        'rsvp_question',
        'allowed_guests',
        'fee_method',
        'fee_currency_id',
        'fee_amount',
        'fee_additional_refund_policy',
        'cancelled_at',
    ];

    /**
     * Automatically cast attributes to given types
     *
     * @var array
     */
    protected $casts = [
        'is_online' => 'boolean',
        'status' => 'integer',
        'fee_method' => 'integer',
        'fee_currency_id' => 'integer',
    ];

    /**
     * Automatically cast attributes to given types
     *
     * @var array
     */
    protected $dates = [
        'starts_at',
        'ends_at',
        'rsvp_starts_at',
        'rsvp_ends_at',
        'announced_at',
        'cancelled_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
    ];

    /**
     * Get route key name for binding routes
     */
    public function getRouteKeyName()
    {
        return 'uid';
    }

    /**
     * Get the string to create a slug from.
     *
     * @return string
     */
    public function getSluggableString()
    {
        return $this->getAttribute('title');
    }

    /**
     * Returns the group of this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault()->withTrashed();;
    }

    /**
     * Returns all the RSVP of this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rsvp()
    {
        return $this->hasMany(EventRSVP::class);
    }
    public function attends()
    {
        return $this->belongsToMany(User::class, 'event_rsvp')
            ->withTimestamps()
            ->withPivot('response');;
    }
    /**
     * Returns all the Saves of this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saves()
    {
        return $this->hasMany(EventSaved::class);
    }

    /**
     * Returns the currency of the event fee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'fee_currency_id', 'id')->withDefault();
    }

    /**
     * Returns the rsvp_starts_at attribute
     *
     * @return string
     */
    public function getRsvpStartsAtAttribute($value)
    {
        return $value ? $value : $this->created_at;
    }

    /**
     * Returns the rsvp_ends_at attribute
     *
     * @return string
     */
    public function getRsvpEndsAtAttribute($value)
    {
        return $value ? $value : $this->starts_at;
    }

    /**
     * Returns the start date attribute as Y-m-d format
     *
     * @return string
     */
    public function getStartDateAttribute()
    {
        return convertToLocal($this->starts_at ?? now(), 'Y-m-d');
    }

    /**
     * Returns the start time attribute as h:i format
     *
     * @return string
     */
    public function getStartTimeAttribute()
    {
        return convertToLocal($this->starts_at ?? now(), 'H:i');
    }

    /**
     * Returns the end date attribute as Y-m-d format
     *
     * @return string
     */
    public function getEndDateAttribute()
    {
        return convertToLocal($this->ends_at ?? now(), 'Y-m-d');
    }

    /**
     * Returns the end time attribute as h:i
     *
     * @return string
     */
    public function getEndTimeAttribute()
    {
        return convertToLocal($this->ends_at ?? now(), 'H:i');
    }

    /**
     * Returns the attendee count
     *
     * @return int
     */
    public function getAttendeeCountAttribute()
    {
        return $this->rsvp()->attending()->count();
    }

    /**
     * Return Default Image Url
     *
     * @return url
     */
    public function getDefaultImage()
    {
        if (config('app.is_demo') || config('app.envato_review')) {
            return 'https://source.unsplash.com/random/300x200?sig='.rand(1, 9999999);
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
        $last_media = $this->getMedia()->last();
        return  $last_media ? $last_media->getFullUrl() : $this->getDefaultImage();
    }

    /**
     * Returns true if the event is draft
     *
     * @return boolean
     */
    public function isDraft()
    {
        return $this->status == $this::DRAFT;
    }

    /**
     * Returns true if the event is published
     *
     * @return boolean
     */
    public function isPublished()
    {
        return $this->status == $this::PUBLISHED;
    }

    /**
     * Returns true if the event's RSVPs are open
     *
     * @return boolean
     */
    public function isRSVPOpen()
    {
        return $this->rsvp_ends_at > Carbon::now();
    }

    /**
     * Returns true if the event is past
     *
     * @return boolean
     */
    public function isPast()
    {
        return $this->ends_at < Carbon::parse(convertToLocal(now(), 'Y-m-d H:i:s'));
    }

    /**
     * Returns true if the event is cancelled
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return $this->cancelled_at;
    }

    /**
     * Update event as cancelled
     *
     * @return boolean
     */
    public function markAsCancelled()
    {
        return $this->update(['cancelled_at' => convertFromLocal(now())]);
    }

    /**
     * Get iCal data for an event
     *
     * @return string
     */
    public function getICalDataForUser($user, $participation_status)
    {
        $calendar = Calendar::create()
            ->event(function ($event) use ($user, $participation_status) {
                $event->name($this->title)
                    ->attendee($user->email, $user->full_name, $participation_status)
                    ->startsAt(Carbon::parse($this->starts_at)->timezone($user->timezone))
                    ->endsAt(Carbon::parse($this->ends_at)->timezone($user->timezone))
                    ->organizer($this->group->createdBy->email, $this->group->createdBy->full_name)
                    ->url(route('groups.events.show', ['group' => $this->group->slug, 'event' => $this->uid]))
                    ->address($this->is_online ? __('Online Event') : $this->address->address_1)
                    ->addressName($this->is_online ? __('Online Event') : $this->address->name);
            });
        $calendar->appendProperty(TextPropertyType::create('METHOD', 'REQUEST'));

        return $calendar->get();
    }

    /**
     * Send reminder to attendees
     *
     * @return void
     */
    public function sendReminderToAttendees()
    {
        try {
            $attendees = $this->rsvp()->attending()->get()->pluck('user_id');
            $notifables = User::whereIn('id', $attendees)->get();
            Notification::send($notifables, new Reminder($this));
        } catch (\Throwable $th) {}
    }

    /**
     * Send reminder to group members
     *
     * @return void
     */
    public function sendAnnouncmentToMembers()
    {
        try {
            $members = $this->group->members;
            Notification::send($members, new Announcement($this));
        } catch (\Throwable $th) {}
    }

    /**
     * Scope a query to filter events by parameters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon                        $starts_at
     * @param \Carbon\Carbon                        $ends_at
     * @param int                                   $type (0 =  any-type, 1 = in-person, 2 = online)
     * @param float                                 $lat
     * @param float                                 $lng
     * @param int                                   $radius
     * @param int                                   $category
     * @param int                                   $topic
     * @param string                                   $place

     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $search, $starts_at, $ends_at, $type, $place, $radius, $category, $topic)
    {

        // Filter by search param
        if ($search) $query = $query->search($search);

        // Filter by date
        if ($starts_at) $query = $query->from(Carbon::parse($starts_at)->startOfDay());
        if ($ends_at) $query = $query->to(Carbon::parse($ends_at)->endOfDay());

        // Filter by type
        if ($type == 0) $query = $query->whereIn('is_online', [0, 1]); // any type
        else if ($type == 1) $query = $query->where('is_online', 0); // online only
        else if ($type == 2) $query = $query->where('is_online', 1); // in-person only

        // Filter by distance
//        if ($lat && $lng) $query = $query->distance($lat, $lng, $radius);
        if ($place) $country =ltrim( array_values(array_slice(explode(',',$place), -1))[0]);
        $state =ltrim(array_values(array_slice(explode(',',$place), -2))[0]);
            $query= $query->with('addresses')->whereHas('addresses', function($q) use ($place,$state){
                $q->where('name', 'like', '%' . $place . '%')->orwhere('state', 'like', '%' . $state. '%');

        });
//
        // Filter by category
        if ($category) {
            $topics_ids = Topic::where('topic_category_id', $category)->pluck('id')->toArray();
            if ($topics_ids && !empty($topics_ids)) {
                $query = $query->whereHas('group', function($q) use ($topics_ids) {
                    $q->withAnyTopics($topics_ids);
                });
            }
        }

        // Filter by topic
        if ($topic) {
            $query = $query->whereHas('group', function($q) use ($topic) {
                $q->withAllTopics($topic);
            });
        }

        return $query;
    }

    /**
     * Scope query with all the given search param.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $param
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($builder, $param)
    {
        if (!empty($param)) {
            $builder->where('title', 'like', '%'.$param.'%')
                ->orWhere('description', 'like', '%'.$param.'%');
        }

        return $builder;
    }

    /**
     * Scope a query to only include events in given location and distance
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
     * Scope a query to only include draft events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', $this::DRAFT);
    }

    /**
     * Scope a query to only include published events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', $this::PUBLISHED);
    }

    /**
     * Scope a query to only include events starting from given date
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon                        $starts_at
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFrom($query, $starts_at)
    {
        return $query->where('starts_at', '>', $starts_at);
    }

    /**
     * Scope a query to only include events ends to given date
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon                        $ends_at
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTo($query, $ends_at)
    {
        return $query->where('ends_at', '<', $ends_at);
    }

    /**
     * Scope a query to only include events starts at given date
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon                        $starts_at
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsAt($query, $starts_at)
    {
        return $query->whereDate('starts_at', $starts_at);
    }

    /**
     * Scope a query to only include past events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('starts_at', '<', convertToLocal(now(), 'Y-m-d H:i:s'))->notCancelled()->orderBy('starts_at');
    }

    /**
     * Scope a query to only include upcoming events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', convertToLocal(now(), 'Y-m-d H:i:s'))->notCancelled()->orderBy('starts_at');
    }

    /**
     * Scope a query to only include cancelled events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled($query)
    {
        return $query->whereNotNull('cancelled_at');
    }

    /**
     * Scope a query to only include not cancelled events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCancelled($query)
    {
        return $query->whereNull('cancelled_at');
    }

    /**
     * Scope a query to only include online events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', 1);
    }

    /**
     * Scope a query to only include attending events of given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserAttending($query, $user)
    {
        return $query->whereHas('rsvp', function($query) use ($user){
            $query->forUser($user)->attending();
        });
    }

    /**
     * Scope a query to only include attendings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttendingResponses($query)
    {
        return $query->rsvp()->attending();
    }

    /**
     * Scope a query to only include not attendings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAttendingResponses($query)
    {
        return $query->rsvp()->notAttending();
    }

    /**
     * Scope a query to only include saved events of given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSavedEvents($query, $user)
    {
        return $query->whereHas('saves', function($query) use ($user){
            $query->forUser($user);
        });
    }

    /**
     * Scope a query to search of group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByGroup($query, $search)
    {
        return $query->whereHas('group', function($query) use ($search){
            $query->search($search);
        });
    }

}
