<?php

namespace App\Events\Group;

use App\Models\GroupMembership;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 
class MembershipCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    /**
     * The order instance.
     *
     * @var \App\Models\GroupMembership
     */
    public $membership;
 
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\GroupMembership  $membership
     * @return void
     */
    public function __construct(GroupMembership $membership)
    {
        $this->membership = $membership;
    }
}