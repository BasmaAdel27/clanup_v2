@extends('layouts.app', [
    'seo_title' => __('Homepage'),
])

@section('content')
    <div class="container py-4">
        @if ($auth_user)
            <section>
                <div class="row mb-3">
                    <div class="col">
                        <h3 class="h1">{{ __('Suggested Events') }}</h3>
                    </div>
                    <div class="col-md-4 d-lg-flex align-items-center justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('suggested_events') }}">
                            {{ __('See all your events') }}
                        </a>
                    </div>
                </div>
                @include('application.groups.events._event_slider', ['events' => $suggested_events])
            </section>

            <section>
                <div class="row mb-3">
                    <div class="col">
                        <h3 class="h1">{{ __('Suggested Groups') }}</h3>
                    </div>
                    <div class="col-md-4 d-lg-flex align-items-center justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('suggested_groups') }}">
                            {{ __('See all your groups') }}
                        </a>
                    </div>
                </div>
                <div class="row mb-4">
                    @foreach ($suggested_groups as $group)
                        <div class="col-12 col-lg-3">
                            @include('application.groups._group_suggested', ['groups' => $group])
                        </div>
                    @endforeach
                </div>
            </section>

            @endif
        @if ($auth_user and $auth_user->isOrganizerOfAnyGroup() && count($events_from_groups_you_organize) > 0)
            <section>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="h1">{{ __('Events you organize') }}</h3>
                    </div>
                    <div class="col-md-4 d-lg-flex align-items-center justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('events', ['menu' => 'from_groups_you_organize']) }}">
                            {{ __('See all your events') }}
                        </a>
                    </div>
                </div>

                @include('application.groups.events._event_slider', ['events' => $events_from_groups_you_organize])
            </section>
        @endif

        @if ($auth_user and $auth_user->isOrganizerOfAnyGroup() && count($organized_groups) > 0)
            <section>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h3 class="h1">{{ __('Groups you organize') }}</h3>
                    </div>
                    <div class="col-md-4 d-lg-flex align-items-center justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('groups') }}">
                            {{ __('See all your groups') }}
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    @foreach ($organized_groups as $group)
                        <div class="col-12 col-lg-3">
                            @include('application.groups._group_info_card', ['group' => $group])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($auth_user)
            <section>
                <div class="row mb-3">
                    <div class="col">
                        <h3 class="h1">{{ __('You\'re attending') }}</h3>
                    </div>
                </div>

                @include('application.groups.events._event_slider', ['events' => $events_attending])
            </section>
        @endif

        @if ($auth_user)
            <section>
                <div class="row mb-3">
                    <div class="col">
                        <h3 class="h1">{{ __('Events from groups you\'ve joined') }}</h3>
                    </div>
                </div>

                @include('application.groups.events._event_slider', ['events' => $events_from_groups_you_joined])
            </section>
        @endif

        @if (!$auth_user)
            <section>
                <div class="row d-flex align-items-center">
                    <div class="col-md-6 text-center text-lg-start">
                        <h3 class="h1 fs-1">{{ __('Dive in! There are so many things to do on :app_name', ['app_name' => $application_name]) }}</h3>
                        <p class="text-muted mb-0">
                        {{ __('Join a group to meet people, make friends, find support, grow a business, and explore your interests. Thousands of events are happening every day, both online and in person!') }}
                        </p>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <img src="{{ asset('assets/images/homepage/banner.svg') }}" width="450" alt="{{ $application_name }} Banner" />
                    </div>
                </div>
            </section>
        @endif

        <section class="my-5">
            <div class="row d-flex align-items-start gx-5">
                <div class="col-md-6">
                    <h4 class="h1 mb-2 mb-md-4">{{ __('What do you want to do?') }}</h4>
                    @livewire('find.search-component', ['inline' => false], 'home')
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <h4 class="h1 mb-2 mb-md-4">{{ __('Explore events by topic') }}</h4>
                    @include('application.components.topic.topic-list', ['topics' => $topics, 'container_class' => '', 'badge_class' => 'bg-primary', 'link' => true])
                </div>
            </div>
        </section>

        @if (!$auth_user)
            <section class="my-5">
                <div class="row d-flex justify-content-center mb-3">
                    <div class="col-12 col-md-8 text-center">
                        <h3 class="h1">{{ __('How :app_name works', ['app_name' => $application_name]) }}</h3>
                        <p class="mb-0">{{ __('Meet new people who share your interests through online and in-person events. It\'s free to create an account.') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                        <div class="px-0 px-lg-3">
                            <img src="{{ asset('assets/images/homepage/join-a-group.svg') }}" width="120" alt="{{ $application_name }} Banner" />
                            <h3>{{ __('Join a group') }}</h3>
                            <p class="mb-0 text-muted">{{ __('Do what you love, meet others who love it, find your community. The rest is history!') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                        <div class="px-0 px-lg-3">
                            <img src="{{ asset('assets/images/homepage/find-an-event.svg') }}" width="120" alt="{{ $application_name }} Banner" />
                            <h3>{{ __('Find an event') }}</h3>
                            <p class="mb-0 text-muted">{{ __('Events are happening on just about any topic you can think of, from online gaming and photography to yoga and hiking.') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                        <div class="px-0 px-lg-3">
                            <img src="{{ asset('assets/images/homepage/start-a-group.svg') }}" width="120" alt="{{ $application_name }} Banner" />
                            <h3>{{ __('Start a group') }}</h3>
                            <p class="mb-0 text-muted">{{ __('You don\'t have to be an expert to gather people together and explore shared interests.') }}</p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if (count($upcoming_online_events) > 4)
            <section>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h2 class="h1">{{ __('Upcoming online events') }}</h2>
                    </div>
                    <div class="col-md-4 d-lg-flex align-items-center justify-content-end">
                        <a class="btn btn-outline-primary" href="{{ route('find', ['source' => 'EVENTS', 'type' => 2, 'from' => now()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]) }}">
                            {{ __('See all upcoming online events') }}
                        </a>
                    </div>
                </div>

                @include('application.groups.events._event_slider', ['events' => $upcoming_online_events])
            </section>
        @endif

        @if (count($blogs) > 0)
            <section>
                <div class="row d-flex justify-content-center mb-3">
                    <div class="col-12 col-md-8 text-center">
                        <h2 class="h1">{{ __('Stories from :app_name', ['app_name' => $application_name]) }}</h2>
                        <p class="mb-0">{{ __('People on :app_name have fostered community, learned new skills, started businesses, and made life-long friends. Learn how.', ['app_name' => $application_name]) }}</p>
                    </div>
                </div>
                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-md-4 mb-4 hover-animate">
                            @include('application.static.blog._blog_card', ['blog' => $blog])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
@if(count($markers) > 0)
@section('page_body_scripts')
<script>
    window.onload=function (){
        initial();
    }
    function initial() {
        // Show All Events in the Map with Marker
        var eventLatLng = { lat: {{ $markers[0][0] }}, lng: {{ $markers[0][1] }} };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: eventLatLng,
            styles: [
                { elementType: "geometry", stylers: [{ color: "#242f3e" }] },
                { elementType: "labels.text.stroke", stylers: [{ color: "#242f3e" }] },
                { elementType: "labels.text.fill", stylers: [{ color: "#746855" }] },
                {
                    featureType: "administrative.locality",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#d59563" }],
                },
                {
                    featureType: "poi",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#d59563" }],
                },
                {
                    featureType: "poi.park",
                    elementType: "geometry",
                    stylers: [{ color: "#263c3f" }],
                },
                {
                    featureType: "poi.park",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#6b9a76" }],
                },
                {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [{ color: "#38414e" }],
                },
                {
                    featureType: "road",
                    elementType: "geometry.stroke",
                    stylers: [{ color: "#212a37" }],
                },
                {
                    featureType: "road",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#9ca5b3" }],
                },
                {
                    featureType: "road.highway",
                    elementType: "geometry",
                    stylers: [{ color: "#746855" }],
                },
                {
                    featureType: "road.highway",
                    elementType: "geometry.stroke",
                    stylers: [{ color: "#1f2835" }],
                },
                {
                    featureType: "road.highway",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#f3d19c" }],
                },
                {
                    featureType: "transit",
                    elementType: "geometry",
                    stylers: [{ color: "#2f3948" }],
                },
                {
                    featureType: "transit.station",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#d59563" }],
                },
                {
                    featureType: "water",
                    elementType: "geometry",
                    stylers: [{ color: "#17263c" }],
                },
                {
                    featureType: "water",
                    elementType: "labels.text.fill",
                    stylers: [{ color: "#515c6d" }],
                },
                {
                    featureType: "water",
                    elementType: "labels.text.stroke",
                    stylers: [{ color: "#17263c" }],
                },
            ]

        });

        var marker1 = new google.maps.Marker({
            position: eventLatLng,
            map: map,
        });
        var markers=<?php print json_encode($markers) ?>;
        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        for (i = 0; i < markers.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(markers[i][0], markers[i][1]),
                url:markers[i][2],
                map: map
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {

                return function() {
                    infowindow.setContent(markers[i][2]);
                    window.location.href=marker.url
                    infowindow.open(map, marker);
                }

            })(marker, i));

        }
        //

    };
</script>
<script src="{{asset('assets/js/map.js')}}"></script>
@endsection
@endif