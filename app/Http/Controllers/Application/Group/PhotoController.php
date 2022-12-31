<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Http\Requests\Application\Group\Photo\Store;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display the Group Photos Page
     *
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        // Log visit
        $group->visit(auth()->user(), $group);
        
        $photos = $group->media()->paginate();

        return view('application.groups.photos', [
            'group' => $group,
            'photos' => $photos,
        ]);
    } 

    /**
     * Display the Group Photos Store
     *
     * @param  \App\Http\Requests\Application\Group\Photo\Store $request
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('store_photo', $group)) {
            return redirect()->to(route('groups.photos', ['group' => $group->slug]) . '#photos');
        }

        // Save photo to server
        $group->addMediaFromRequest('file')->usingName($request->title)->withCustomProperties(['created_by_id' => $request->user()->id])->toMediaCollection();

        return redirect()->to(route('groups.photos', ['group' => $group->slug]) . '#photos');
    } 

    /**
     * Delete the group photo
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Group $group)
    {
        // Authorize user
        if ($request->user()->cant('delete_photo', $group)) {
            return redirect()->to(route('groups.photos', ['group' => $group->slug]) . '#photos');
        }

        // Find media
        $media = $group->media()->where('id', $request->photo)->first();

        // Delete media
        if ($media) {
            $group->media()->where('id', $request->photo)->delete();
        }

        return redirect()->to(route('groups.photos', ['group' => $group->slug]) . '#photos');
    } 
}
