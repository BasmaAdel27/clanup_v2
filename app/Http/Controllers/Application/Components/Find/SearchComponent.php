<?php

namespace App\Http\Controllers\Application\Components\Find;

use Livewire\Component;

class SearchComponent extends Component
{
    public $inline = true;
    public $search;
    public $place;
    public $lat;
    public $lng;

    public function mount()
    {

        if (request()->has('search')) $this->search = request()->get('search');
        if (session('place')) $this->place = session('place');
        if (session('lat')) $this->lat = session('lat');
        if (session('lng')) $this->lng = session('lng');
       
        // No session value then get current location
        if (!$this->lat || !$this->lng || !$this->place) {
            $position = get_current_location_by_ip(request()->ip());

            // Set component variables
            $this->place = $position->cityName . ', ' . $position->countryName;

            $this->lat = $position->latitude;
            $this->lng = $position->longitude;

            // Set session
            session([

                'place' => $position->cityName . ', ' . $position->countryName,
                'lat' => $position->latitude,
                'lng' => $position->longitude
            ]);
        }
    }

    public function updated($name)
    {

        if (in_array($name, ['place', 'lat', 'lng'])) {
            // Update session
            session([
                'place' => $this->place,
                'lat' => $this->lat,
                'lng' => $this->lng
            ]);
        }
    }

    public function render()
    {
        return view('application.components.find.search-component');
    }
}
