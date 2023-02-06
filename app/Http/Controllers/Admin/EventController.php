<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    /**
     * Display the Events Page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Events
        $events = QueryBuilder::for(Event::class)
            ->allowedFilters([
                AllowedFilter::partial('title'),
                AllowedFilter::scope('group', 'searchByGroup'),
                AllowedFilter::callback('status', function ($query, $value) {
                    switch ($value) {
                        case 'cancelled':
                            $query->whereNull('deleted_at')->cancelled();
                            break;
                        case 'draft':
                            $query->whereNull('deleted_at')->draft();
                            break;
                        case 'past':
                            $query->whereNull('deleted_at')->past();
                            break;
                        case 'upcoming':
                            $query->whereNull('deleted_at')->upcoming();
                            break;
                        case 'deleted':
                            $query->whereNotNull('deleted_at');
                            break;
                    }
                }),
            ])
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->paginate()
            ->appends(request()->query());
        return view('admin.events.index', [
            'events' => $events
        ]);
    }

    /**
     * Delete the Event
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $event = Event::findOrFail($request->event);

        // Delete Event
        $event->delete();

        session()->flash('alert-success', __('Event deleted'));
        return redirect()->route('admin.events');
    }
}
