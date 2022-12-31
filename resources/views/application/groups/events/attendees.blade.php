@extends('layouts.app', [
    'seo_title' => __('Attendees :event', ['event' => $event->title]),
    'seo_description' => substr(strip_tags($event->description ), 0, 180),
    'seo_image' => $event->image,
    'hide_top_footer' => true,
    'hide_bottom_footer' => true
])

@section('content')
    <section>
        @can ('view', $event)
            @livewire('group.event.attendees', ['event' => $event], $event->id)
        @else
            <div class="container">
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8 card">
                        <a class="p-3" href="{{ route('groups.events.show', ['group' => $event->group, 'event' => $event]) }}"> < {{ __('Back to event') }}</a>
                        @include('application.components.visible-only-member')
                    </div>
                </div>
            </div> 
        @endcan
    </section>
@endsection
