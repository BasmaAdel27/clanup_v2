@extends('layouts.app', [
    'seo_title' => __('Start a Group'),
    'seo_description' => __("We'll walk you through a few steps to build your local community"),
    'hide_top_footer' => true, 
    'hide_bottom_footer' => true
])

@section('content')
    <section class="container py-5">
        @livewire('group.start.form')
    </section>
@endsection