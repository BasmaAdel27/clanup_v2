<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    /**
     * Display the Groups Page
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Groups
        $groups = QueryBuilder::for(Group::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('organizer', 'searchOrganizer'),
                AllowedFilter::callback('status', function ($query, $value) {
                    switch ($value) {
                        case 'deleted':
                            $query->whereNotNull('deleted_at');
                            break;
                        case 'public':
                            $query->whereNull('deleted_at')->open();
                            break;
                        case 'closed':
                            $query->whereNull('deleted_at')->closed();
                            break;
                    }
                })
            ])
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());

        return view('admin.groups.index', [
            'groups' => $groups
        ]);
    }

    /**
     * Delete the Group
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $group = Group::findOrFail($request->group);

        // Delete Group
        $group->delete();

        session()->flash('alert-success', __('Group deleted'));
        return redirect()->route('admin.groups');
    }
}
