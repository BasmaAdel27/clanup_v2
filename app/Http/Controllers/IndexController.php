<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified'])->only(['index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $with = [];

        // Check if the user has logged in
        if ($user = $request->user()) {
            // Events from organized groups
            $organized_groups = $user->groups(GroupMembership::EVENT_ORGANIZER);
            $with['organized_groups'] = $organized_groups->take(6)->get();
            $joined_group_ids = $user->groups()->pluck('group_id')->toArray();
            $with['suggested_groups'] = Group::where('group_type',0)->whereNotIn('id',$joined_group_ids)->take(6)->get();
            $organized_group_ids = $organized_groups->pluck('group_id')->toArray();
            $events_attending=Event::userAttending($user)->pluck('group_id')->toArray();
            $with['suggested_events']=Event::whereHas('group',function ($q){
                $q->where('group_type',0);
            })->whereNotIn('group_id', $organized_group_ids)->
            whereNotIn('group_id', $joined_group_ids)->whereNotIn('group_id',$events_attending)->upcoming()->take(8)->get();
            $with['markers']=$with['suggested_events']->map(function ($item, $key) {
               $url=env('APP_URL')."/g/".$item->group->slug."/events/".$item->uid;
                return [$item->getAddressAttribute()->lat, $item->getAddressAttribute()->lng,
                    $url];
            });
//            dd( $with['markers'], $with['suggested_events']);
            $with['events_from_groups_you_organize'] = Event::whereIn('group_id', $organized_group_ids)->upcoming()->take(8)->get();
            $with['events_attending'] = Event::userAttending($user)->upcoming()->take(8)->get();
            $with['events_from_groups_you_joined'] = Event::whereIn('group_id', $joined_group_ids)->upcoming()->take(8)->get();
        }
        $with['upcoming_online_events'] = Event::from(now()->startOfDay())->to(now()->endOfDay())->online()->notCancelled()->orderBy('starts_at')->take(8)->get();
        $with['topics'] = Topic::inRandomOrder()->limit(5)->get();
        $with['blogs'] = Blog::take(3)->latest()->get();


        return view('application.index', $with);
    }

    public function home(Request $request){
        $with=[];
        if ($user = $request->user()) {
            // Events from organized groups
            $organized_groups = $user->groups(GroupMembership::EVENT_ORGANIZER);
            $with['organized_groups'] = $organized_groups->take(6)->get();
            $joined_group_ids = $user->groups()->pluck('group_id')->toArray();
            $with['suggested_groups'] = Group::where('group_type',0)->whereNotIn('id',$joined_group_ids)->take(6)->get();
            $organized_group_ids = $organized_groups->pluck('group_id')->toArray();
            $events_attending=Event::userAttending($user)->pluck('group_id')->toArray();
            $with['suggested_events']=Event::whereHas('group',function ($q){
                $q->where('group_type',0);
            })->whereNotIn('group_id', $organized_group_ids)->
            whereNotIn('group_id', $joined_group_ids)->whereNotIn('group_id',$events_attending)->upcoming()->take(8)->get();
            $with['markers']=$with['suggested_events']->map(function ($item, $key) {
                $url=env('APP_URL')."/g/".$item->group->slug."/events/".$item->uid;
                return [$item->getAddressAttribute()->lat, $item->getAddressAttribute()->lng,
                    $url];
            });
            $with['events_from_groups_you_organize'] = Event::whereIn('group_id', $organized_group_ids)->upcoming()->take(8)->get();
            $with['events_attending'] = Event::userAttending($user)->upcoming()->take(8)->get();
            $with['events_from_groups_you_joined'] = Event::whereIn('group_id', $joined_group_ids)->upcoming()->take(8)->get();
        }
        $with['upcoming_online_events'] = Event::from(now()->startOfDay())->to(now()->endOfDay())->online()->notCancelled()->orderBy('starts_at')->take(8)->get();
        $with['topics'] = Topic::inRandomOrder()->limit(5)->get();
        $with['blogs'] = Blog::take(3)->latest()->get();

        return view('application.index', $with);
    }

    /**
     * Show the application demo page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function demo()
    {
        // If demo mode is not active then deactivate demo page
        if (config('app.is_demo')) {
            return view('layouts.demo');
        };

        return redirect('/');
    }

    /**
     * Change language and store the locale pref in session
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function change_language(Request $request){
        if (Auth::check()) {
            $user = $request->user();
            $user->setSetting('locale', $request->locale);
        } else {
            session()->put('locale', $request->locale);
        }

        return redirect()->back();
    }
}