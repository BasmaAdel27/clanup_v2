@extends('layouts.app', [
    'seo_title' => __('My Events'),
])

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-3 mb-4">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-filter"></i>
                                {{ __('Filters') }}
                            </div>
                        </div>
                        <nav class="nav side-nav flex-row flex-nowrap flex-lg-column flex-lg-wrap">
                            <a class="nav-link text-nowrap @if ($menu == 'attending') fw-bold text-primary @endif" href="{{ route('events', ['menu' => 'attending']) }}">
                                {{ __('Attending') }}
                            </a>
                            <a class="nav-link text-nowrap @if ($menu == 'saved') fw-bold text-primary @endif" href="{{ route('events', ['menu' => 'saved']) }}">
                                {{ __('Saved') }}
                            </a>
                            <a class="nav-link text-nowrap @if ($menu == 'from_groups_you_organize') fw-bold text-primary @endif" href="{{ route('events', ['menu' => 'from_groups_you_organize']) }}">
                                {{ __('From groups you organize') }}
                            </a>
                            <a class="nav-link text-nowrap @if ($menu == 'from_groups_you_joined') fw-bold text-primary @endif" href="{{ route('events', ['menu' => 'from_groups_you_joined']) }}">
                                {{ __('From groups you\'ve joined') }}
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="col-lg-9">
                    <h1>{{ __('My Events') }}</h1>

                    <div class="btn-group w-100 my-3">
                        <a class="btn {{ $tab == 'upcoming' ? 'btn-primary' : '' }}" href="{{ route('events', ['menu' => $menu, 'tab' => 'upcoming']) }}">{{ __('Upcoming') }}</a>
                        <a class="btn {{ $tab == 'past' ? 'btn-primary' : '' }}" href="{{ route('events', ['menu' => $menu, 'tab' => 'past']) }}">{{ __('Past') }}</a>
                    </div>

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
