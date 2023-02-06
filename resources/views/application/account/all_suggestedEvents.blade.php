@extends('layouts.app', [
    'seo_title' => __('Suggested Events'),
])

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-9">
                    <h1>{{ __('Suggested Events') }}</h1>

                    <div class="scrolling-pagination">
                        @foreach ($events as $event)
                            @include('application.groups.events._event_card', ['event' => $event, 'list_view' => true])
                        @endforeach

                        <div class="d-none">
                            {{ $events->withQueryString()->links() }}
                        </div>
                    </div>

                    @if (count($events) <= 0)
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                            <i class="far fa-calendar-alt fs-4"></i>
                            <p class="mb-0 mt-2">{{ __('No events yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
