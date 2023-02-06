<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EventRSVP extends Model
{
    // Table name
    protected $table = 'event_rsvp';

    // RSVP Responses
    const NOT_COMING = 0; // default
    const COMING = 1;
    const WAITING_APPROVAL = 2;

    // Pay Status
    const NONE = 0;
    const EXEMPT = 1;
    const PENDING = 2;
    const PAID = 3;
    const REFUNDED = 4;
    const PARTIAL_REFUND = 5;
    const REFUND_PENDING = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'response',
        'pay_status',
        'paid_amount',
        'refunded_amount',
        'guests',
        'question_answer',
        'is_attended',
    ];

    /**
     * Automatically cast attributes to given types
     *
     * @var array
     */
    protected $casts = [
        'is_attended' => 'boolean',
        'guests' => 'integer',
    ];


    /**
     * Returns the event of this RSVP
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class)->withDefault();
    }

    /**
     * Returns the user of this RSVP
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Mark as Coming
     *
     * @return $this
     */
    public function isComing()
    {
        return $this->response == self::COMING;
    }

    /**
     * Mark as Coming
     *
     * @return $this
     */
    public function markAsComing()
    {
        return $this->update(['response' => self::COMING]);
    }

    /**
     * Mark as Not Coming
     *
     * @return $this
     */
    public function markAsNotComing()
    {
        return $this->update(['response' => self::NOT_COMING]);
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
     * Scope query with all user by given search param.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchUser($query, $search)
    {
        if (empty($search)) return $query;
        return $query->whereHas('user', function($q) use ($search){
            $q->search($search);
        });
    }

    /**
     * Scope query only attending RSVP's
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttending($query)
    {
        return $query->where('response', self::COMING);
    }

    /**
     * Scope query only not attending RSVP's
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAttending($query)
    {
        return $query->where('response', self::NOT_COMING);
    }

    /**
     * Scope query only waiting approval RSVP's
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaitingApproval($query)
    {
        return $query->where('response', self::WAITING_APPROVAL);
    }

    /**
     * Scope query of upcoming events
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->whereHas('event', function($query){
            $query->upcoming();
        });
    }

    /**
     * Scope query of past events
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->whereHas('event', function($query){
            $query->past();
        });
    }
}
