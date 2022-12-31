<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupMembership extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'user_id',
        'membership',
        'notification_interval',
        'notifed_at',
    ]; 

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'notifed_at'
    ];

    // Membership levels
    const ORGANIZER = 100; // organizer
    const CO_ORGANIZER = 90; // co-organizer
    const ASSISTANT_ORGANIZER = 80; // assistant organizer
    const EVENT_ORGANIZER = 70; // event organizer
    const MEMBER = 20; // active member
    const CANDIDATE = 10; // member asked to be part of the group, but it has not been confirmed yet
    const INVITED = 0;  // member invited by a group admin    
    const UNSUBSCRIBED = -10; // user left the group
    const DECLINED = -15;  // user did not accept an invitation
    const REMOVED = -20; // removed by admin for another reason
    const BLACKLISTED = -30; // member is blacklisted and cannot join the group again

    /**
     * Returns the user of this membership.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Returns the group of this membership.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class)->withDefault();
    }

    /**
     * Returns the role name
     * 
     * @return string
     */
    public function getRoleName()
    {
        if ($this->membership == self::ORGANIZER)
            return __('Organizer');
        elseif ($this->membership == self::CO_ORGANIZER)
            return __('Co-organizer');
        elseif ($this->membership == self::ASSISTANT_ORGANIZER)
            return __('Assistant organizer');
        elseif ($this->membership == self::EVENT_ORGANIZER)
            return __('Event organizer');
        elseif ($this->membership == self::MEMBER)
            return __('Member');
        elseif ($this->membership == self::CANDIDATE)
            return __('Candidate');
        elseif ($this->membership == self::UNSUBSCRIBED)
            return __('Unsubscribed');
        else
            return '';
    }

    /**
     * Scope query with the group given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $group
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForGroup($query, Group $group)
    {
        return $query->where('group_id', $group->id);
    }

    /**
     * Scope query with the user given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope query with the membership given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $operator
     * @param mixed                                 $membership
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForMembership($query, $operator = '=', $membership)
    {
        return $query->where('membership', $operator, $membership);
    }
}