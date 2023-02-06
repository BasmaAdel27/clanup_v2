<?php

namespace App\Http\Controllers\Application\Account;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GroupMembership;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display the My Events Page
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $menu = $request->get('menu', 'attending');
        $tab = $request->get('tab', 'upcoming');

        // Get query by menu
        switch ($menu) {
            case 'attending':
                $query = Event::userAttending($user);
                break;
            case 'saved':
                $query = Event::savedEvents($user);
                break;
            case 'from_groups_you_organize':
                $group_ids = $user->groups(GroupMembership::EVENT_ORGANIZER)->pluck('group_id')->toArray();
                $query = Event::whereIn('group_id', $group_ids);
                break;
            case 'from_groups_you_joined':
                $group_ids = $user->groups()->pluck('group_id')->toArray();
                $query = Event::whereIn('group_id', $group_ids);
                break;
        }

        // Get query by tab
        switch ($tab) {
            case 'upcoming':
                $query = $query->upcoming();
                break;
            case 'past':
                $query = $query->past();
                break;
        }

        // Paginate query
        $events = $query->paginate();

        return view('application.account.events', [
            'events' => $events,
            'menu' => $menu,
            'tab' => $tab,
        ]);
    }


    public function suggested_events(Request $request){
        $user=$request->user();
        $limit=10;
        $joined_group_ids = $user->groups()->pluck('group_id')->toArray();
        $events_attending=Event::userAttending($user)->pluck('group_id')->toArray();
        $organized_group_ids = $user->groups(GroupMembership::EVENT_ORGANIZER)->pluck('group_id')->toArray();
        $events=Event::whereHas('group',function ($q){
            $q->where('group_type',0);
        })->whereNotIn('group_id', $organized_group_ids)->
        whereNotIn('group_id', $joined_group_ids)->whereNotIn('group_id',$events_attending)->upcoming()->paginate($limit);

        return view('application.account.all_suggestedEvents',['events'=>$events]);
    }
}
