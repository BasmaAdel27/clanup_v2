@extends('layouts.app', [
    'seo_title' => __('Edit :event', ['event' => $event->title]),
    'hide_top_footer' => true,
    'hide_bottom_footer' => true
])

@section('content') 
    @livewire('group.event.form', ['group' => $group, 'event' => $event], $event->id)
@endsection