<?php

namespace App\Http\Controllers\Application\Components\Find;

use App\Models\Event;
use App\Models\Group;
use Livewire\Component;

class Container extends Component
{
    // Filters
    public $source = 'EVENTS';
    public $search;
    public $place;
    public $lat;
    public $lng;
    public $date;
    public $from;
    public $to;
    public $type = 0;
    public $distance = 1000;
    public $category = 0;
    public $topic = 0;

    // For pagination
    public $limit = 15;
    public $count;

    // Define query parameters
    protected $queryString = [
        'source',
        'search' => ['except' => ''],
        'place' => ['except' => ''],
        'lat' => ['except' => ''],
        'lng' => ['except' => ''],
        'type' => ['except' => 0],
        'distance' => ['except' => 1000],
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'category' => ['except' => 0],
        'topic' => ['except' => 0]
    ];


    public function mount()
    {
        // Set location parameters if they are not already set
        if (get_system_setting('google_places_api_key')) {
            if (!$this->place && session('place')) $this->place = session('place');
            if (!$this->lat && session('lat')) $this->lat = session('lat');
            if (!$this->lng && session('lng')) $this->lng = session('lng');
        }

        // Return if the source is unknown
        if (!in_array($this->source, ['EVENTS', 'GROUPS'])) return redirect()->route('find', ['source' => 'EVENTS']);
    }

    // Reset and clear all filters
    public function resetFilters()
    {
        $this->reset(['type', 'distance', 'from', 'to', 'date', 'category', 'topic']);
    }

    // Set from and to variables after DateRangePicker updated
    public function updatedDate()
    {
        if (str_contains($this->date, ' to ')) {
            $dates = explode(' to ' , $this->date);
            $this->from = $dates[0];
            $this->to = $dates[1];
        }
    }

    // Set topic variable as 0 if the category updated since we use same select input for both category and topic
    public function updatedCategory()
    {
        $this->topic = 0;
    }

    public function loadMore() {
        $this->limit += 15;
    }

    public function render()
    {
        if ($this->source == 'EVENTS') {
            $data = Event::upcoming()->published()->filter($this->search, $this->from, $this->to, $this->type, $this->place, $this->distance, $this->category, $this->topic);
            $allData=$data->take($this->limit)->get();
            $markers=$allData->map(function ($item, $key){
           return [$item->getAddressAttribute()->lat,$item->getAddressAttribute()->lng];
       });
            $this->dispatchBrowserEvent('contentChanged',['data' => $data->take($this->limit)->get(),'markers'=>$markers]);
        } else if ($this->source == 'GROUPS') {
            $data = Group::filter($this->search, $this->place, $this->category, $this->topic);
            $allData=$data->take($this->limit)->get();
            $markers=$allData->map(function ($item, $key) {
                return [$item->getAddressAttribute()->lat, $item->getAddressAttribute()->lng];
            });
        }
        $this->count = $data->count();

        return view('application.components.find.container', ['data' => $data->take($this->limit)->get(),'markers'=>$markers]);
    }
}
