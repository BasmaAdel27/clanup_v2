<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;

class MemberController extends Controller
{
    /**
     * Display the Group Members Page
     *
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);
        
        return view('application.groups.members', [
            'group' => $group
        ]);
    }
}
