<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSaved extends Model
{
    // Table name
    protected $table = "event_saved";
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'ip',
        'user_agent',
    ];

    /**
     * Returns the event of this saved
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class)->withDefault();
    }

    /**
     * Returns the user of this saved 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Scope query with all the given event model
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\Event                     $event
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEvent($query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    /**
     * Scope query with all the given user model
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User                      $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope query with all the given ip address
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $ip
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForIp($query, $ip)
    {
        return $query->where('ip', $ip);
    }

    /**
     * Scope query with all the given user agent.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $user_agent
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUserAgent($query, $user_agent)
    {
        return $query->where('user_agent', $user_agent);
    }
}