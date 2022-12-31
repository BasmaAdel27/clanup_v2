<?php

namespace App\Http\Controllers\Application\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GroupMembership;
use App\Models\Group;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the Profile Page
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $groups = [];
        if ($user->getSetting('show_groups_on_profile')) {
            $groups = $user->groups(GroupMembership::MEMBER, Group::OPEN, '>=', '=')->paginate(10);
        }

        return view('application.account.profile.index', ['member' => $user, 'groups' => $groups]);
    } 
}
