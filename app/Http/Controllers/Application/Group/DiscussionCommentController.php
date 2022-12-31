<?php

namespace App\Http\Controllers\Application\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Group\DiscussionComment\Store;
use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\Group;

class DiscussionCommentController extends Controller
{
    /**
     * Store the Discussion Comment
     *
     * @param  App\Http\Requests\Application\Group\DiscussionComment\Store $request
     * @param  \App\Models\Discussion $discussion
     * @param  \App\Models\Group $group
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request, Group $group, Discussion $discussion)
    {
        // Authorize user
        if ($request->user()->cant('create', [DiscussionComment::class, $group])) {
            return redirect()->to(route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) . '#comments');
        }

        // Store the comment
        DiscussionComment::create([
            'user_id' => $request->user()->id,
            'discussion_id' => $discussion->id,
            'content' => $request->comment,
        ]);

        return redirect()->to(route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) . '#comments');
    }

    /**
     * Delete the Discussion Comment
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Group $group
     * @param  \App\Models\Discussion $discussion
     * @param  \App\Models\DiscussionComment $comment
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, Group $group, Discussion $discussion, DiscussionComment $comment)
    {
        // Authorize user
        if ($request->user()->cant('delete', $comment)) {
            return redirect()->to(route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) . '#comments');
        }

        // Delete Comment
        $comment->delete();

        return redirect()->to(route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) . '#comments');
    } 
}
