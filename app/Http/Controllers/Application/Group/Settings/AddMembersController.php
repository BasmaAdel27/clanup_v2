<?php

namespace App\Http\Controllers\Application\Group\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\Settings\Basic\Update;
use App\Http\Requests\Application\Group\Settings\Basic\Delete;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Group;
use \App\Models\GroupMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddMembersController extends Controller
{

    public function index(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }
        $members= $group->members()->pluck('user_id')->toArray();
        $allusers=array_diff(User::pluck('id')->toArray(),$members);
        $users=User::select(DB::raw("CONCAT (first_name,' ',last_name) as name, id"))->find($allusers);

        return view('application.groups.settings.addMembers', [
            'group' => $group,
            'users'=>$users,
            'members'=>$members

        ]);
    }

    public function update(Request $request, Group $group)
    {
        // Authorize user
//        if ($request->user()->cant('update', $group)) {
        $auth_user=Auth::user();
        if (!Auth::user() && !$auth_user->isOrganizerOf($group)) {
            return redirect()->route('groups.about', ['group' => $group->slug]);
        }

        foreach ($request['user_id'] as $user) {
            $data[$user]['membership'] =  20;
        }
       $group->membersship()->attach($data);




        session()->flash('alert-success', __('add members updated'));
        return redirect()->route('groups.settings.addMembers', ['group' => $group->slug]);
    }


}
