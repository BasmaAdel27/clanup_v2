<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRSVP;
use App\Models\Group;
use App\Models\User;
use App\Services\CSVExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display upcoming events page of group
     *
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */

    public function addAttendes(Request $request,Group $group,Event $event){
        if ($request->user()->cant('create', [Event::class, $group])) {
            return redirect()->to(route('groups.events', ['group' => $group->slug]) . '#events');
        }
        $users=User::select(DB::raw("CONCAT (first_name,' ',last_name) as name, id"))->get();
        $members=EventRSVP::where('event_id',$event->id)->pluck('user_id')->toArray();
        return view('application.groups.events.addAttendees', [
            'group' => $group,
            'event'=>$event,
            'users'=>$users,
            'members'=>$members

        ]);
    }

    public function update(Request $request,Group $group,Event $event){
        if ($request->user()->cant('create', [Event::class, $group])) {
            return redirect()->to(route('groups.events', ['group' => $group->slug]) . '#events');
        }
        foreach ($request['user_id'] as $user) {
            $data[$user]['response'] =  1;
        }
        $event->attends()->sync($data);
            session()->flash('alert-success', __('add members updated'));
        return redirect()->back();
    }
    public function upcoming_events(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);

        $events = $group->events()->upcoming()->paginate();

        return view('application.groups.events', [
            'group' => $group,
            'events' => $events,
            'tab' => 'upcoming',
        ]);
    }

    /**
     * Display draft events page of group
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function draft_events(Request $request, Group $group)
    {
        $user = $request->user();

        // Check user has permission to view draft events
        if ($user->hasOrganizerRolesOf($group)) {
            $events = $group->events()->draft()->paginate();
        } else {
            return redirect()->route('groups.events', ['group' => $group->slug]);
        }

        return view('application.groups.events', [
            'group' => $group,
            'events' => $events,
            'tab' => 'draft',
        ]);
    }

    /**
     * Display past events page of group
     *
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function past_events(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);

        $events = $group->events()->past()->paginate();

        return view('application.groups.events', [
            'group' => $group,
            'events' => $events,
            'tab' => 'past',
        ]);
    }

    /**
     * Display create event page
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('create', [Event::class, $group])) {
            return redirect()->to(route('groups.events', ['group' => $group->slug]) . '#events');
        }

        return view('application.groups.events.create', [
            'group' => $group
        ]);
    }

    /**
     * Display event details page
     *
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, Event $event)
    {
        // Log visit
        $event->visit(auth()->user(), $group);

        $sponsors = $group->sponsors;
        $attendees = $event->rsvp()->attending()->latest()->take(12)->get();

        return view('application.groups.events.details', [
            'group' => $group,
            'event' => $event,
            'sponsors' => $sponsors,
            'attendees' => $attendees,
        ]);
    }


    /**
     * Display edit event page
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Group $group, Event $event)
    {
        // Authorize user
        if ($request->user()->cant('update', $event)) {
            return redirect()->to(route('groups.events', ['group' => $group->slug]) . '#events');
        }

        return view('application.groups.events.edit', [
            'group' => $group,
            'event' => $event,
        ]);
    }

    /**
     * Display event attendees page
     *
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function attendees(Group $group, Event $event)
    {
        return view('application.groups.events.attendees', [
            'group' => $group,
            'event' => $event,
        ]);
    }

    /**
     * Export Attendees
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function export_attendees(Request $request, Group $group, Event $event)
    {
        $attendees = $event->rsvp()->whereIn('response', [EventRSVP::COMING, EventRSVP::NOT_COMING]);
        $csv_service = new CSVExport();
        $can_see_email = $request->user()->can('see_attendee_email', $group);

        return $csv_service->file($event->slug.'-attendees')
            ->source($attendees)
            ->chunks(50)
            ->header([
                [__('Name'), __('Email'), __('Title'), __('RSVP'), __('Guests'), __('Answer'), __('RSVPed on'), __('Profile Link')]
            ])->callback(function($rsvp) use ($group, $can_see_email) {
                return [
                    $rsvp->user->full_name,
                    $can_see_email ? $rsvp->user->email : $rsvp->user->username,
                    $rsvp->user->getRoleOf($group),
                    $rsvp->isComing() ? __('Yes') : __('No'),
                    $rsvp->guests,
                    $rsvp->question_answer,
                    $rsvp->created_at->format('M d, Y'),
                    route('profile', $rsvp->user->username),
                ];
            })->export();
    }

    /**
     * Close RSVP Time for the Event
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function close_rsvp(Request $request, Group $group, Event $event)
    {
        // Authorize user
        if ($request->user()->cant('update', $event)) {
            return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
        }

        // Update event
        $event->update(['rsvp_ends_at' => now()]);

        return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
    }

    /**
     * Open RSVP Time for the Event
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function open_rsvp(Request $request, Group $group, Event $event)
    {
        // Authorize user
        if ($request->user()->cant('update', $event)) {
            return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
        }

        // Update event
        $event->update(['rsvp_ends_at' => $event->starts_at]);

        return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
    }

    /**
     * Cancel or Delete the event
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, Group $group, Event $event)
    {
        // Authorize user
        if ($request->user()->cant('delete', $event)) {
            return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
        }

        // Update event
        if ($request->confirm_cancel == 'cancel') {
            $event->markAsCancelled();
            return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
        } else if ($request->confirm_cancel == 'delete') {
            $event->delete();
        }

        return redirect()->route('groups.events', ['group' => $group->slug]);
    }

    /**
     * Announce the event
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function announce(Request $request, Group $group, Event $event)
    {
        // Authorize user
        if ($request->user()->cant('update', $event)) {
            return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
        }

        // Announce the event if it is not already announced
        if (!$event->announced_at) {
            $event->sendAnnouncmentToMembers();
            $event->update(['announced_at' => now()]);
        }

        return redirect()->route('groups.events.show', ['group' => $group->slug, 'event' => $event->uid]);
    }
}
