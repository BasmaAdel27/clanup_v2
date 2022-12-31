@if (get_system_setting('google_places_api_key')) 
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ get_system_setting('google_places_api_key') }}&libraries=places&callback=initAutocomplete"></script>
    <script src="{{ asset('assets/js/autocomplete.js') }}"></script>
@endif
