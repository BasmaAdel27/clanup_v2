@extends('layouts.app', [
    'seo_title' => $source == 'EVENTS' ? __('Explore :topic Events', ['topic' => $category . $topic]) : __('Explore :topic Groups', ['topic' => $category . $topic]),
    'fixed_header' => true
])

@section('content')
    <div>
        <section class="container py-5">
            @livewire('find.container')
        </section>
    </div>
@endsection