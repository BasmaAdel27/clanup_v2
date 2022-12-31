@extends('layouts.app', [
    'seo_title' => __('Schedule an event'),
    'hide_top_footer' => true,
    'hide_bottom_footer' => true
])

@section('content')
    @livewire('group.event.form', ['group' => $group], $group->id)
@endsection
