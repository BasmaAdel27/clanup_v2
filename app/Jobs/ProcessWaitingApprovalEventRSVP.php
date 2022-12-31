<?php

namespace App\Jobs;

use App\Models\EventRSVP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWaitingApprovalEventRSVP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $membership;
    protected $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($membership, $action)
    {
        $this->membership = $membership;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $events = $this->membership->group->events()->published()->upcoming()->pluck('id')->get();
        $rsvp = EventRSVP::whereIn('event_id', $events)->waitingApproval()->get();
        foreach ($rsvp as $rsvp) {
            $rsvp->{$this->action}();
        }
    }
}
