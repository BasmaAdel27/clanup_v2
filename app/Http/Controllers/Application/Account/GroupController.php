<?php

namespace App\Http\Controllers\Application\Account;

use App\Http\Controllers\Controller;
use App\Models\GroupMembership;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display the My Groups Page
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $request->tab ?? 'organizer';
        $limit = 10;

        // Get groups by tab
        if ($tab == 'organizer') {
            $groups = $user->groups(GroupMembership::EVENT_ORGANIZER)->paginate($limit);
        } else {
            $tab = 'member';
            $groups = $user->groups(GroupMembership::MEMBER, Group::OPEN, '=')->paginate($limit);
        }

        return view('application.account.my_groups', [
            'groups' => $groups,
            'tab' => $tab,
        ]);
    } 
}
