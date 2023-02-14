@extends('layouts.app', [
    'seo_title' => __('Suggested Events'),
])
@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-9">
                    <h1>{{ __('Suggested Events') }}</h1>
                    @if (count($events) > 0)

                    <div id="map" style="height: 400px; width:100%;margin-bottom: 30px" ></div>
                    <div class="scrolling-pagination">
                        @foreach ($events as $event)
                            @include('application.groups.events._event_card', ['event' => $event, 'list_view' => true])
                        @endforeach

                        <div class="d-none">
                            {{ $events->withQueryString()->links() }}
                        </div>
                    </div>
                    @endif
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
@if(count($markers)>0)
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