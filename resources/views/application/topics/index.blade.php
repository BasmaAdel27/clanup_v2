@extends('layouts.app', [
    'seo_title' => __('Topics'),
])

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">{{ __('Topics') }}</h1>

        @foreach ($topic_categories as $topic_category)
            <h2 class="mb-3">{{ $topic_category->name }}</h2>
            <div class="row">
                @foreach ($topic_category->topics as $topic)
                    <div class="col-3 mb-2">
                        <a href="{{ route('find', ['source' => 'EVENTS', 'topic' => $topic->id]) }}">{{ $topic->name }}</a>
                    </div>
                @endforeach
            </div>
            <hr>
        @endforeach
    </div>
@endsection
