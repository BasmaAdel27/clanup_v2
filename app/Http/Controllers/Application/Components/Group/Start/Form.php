<?php

namespace App\Http\Controllers\Application\Components\Group\Start;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Topic;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;

    // Default Step
    public $step = 'location';
    
    // Location
    public $location_name;
    public $place_name;
    public $country;
    public $state;
    public $city;
    public $postal_code;
    public $formatted_address;
    public $lat;
    public $lng;

    // Topics
    public $search;
    public $selected_interests = [];
    public $recommended_interests = [];
    public $selected_topics_max_reached;

    // Group name & describe
    public $group_name;
    public $group_describe;

    protected $rules = [
        'location_name' => 'required|max:255',
        'group_name' => 'required|max:120',
        'group_describe' => 'required|max:5000',
    ];

    public function mount()
    {
        $this->recommended_interests = Topic::where('name', 'like', '%'.$this->search.'%')->inRandomOrder()->limit(10)->get();
    }

    public function detachTopic($topic)
    {
        if (isset($this->selected_interests[$topic['id']])) {
            unset($this->selected_interests[$topic['id']]);
        }

        if (count($this->selected_interests) < 15) {
            $this->selected_topics_max_reached = false;
        }
    }

    public function attachTopic($topic)
    {
        $selected_topics_count = count($this->selected_interests) + 1;

        if (!isset($this->selected_interests[$topic['id']]) && $selected_topics_count <= 15) {
            $this->selected_interests[$topic['id']] = $topic;
        }

        if ($selected_topics_count == 15) {
            $this->selected_topics_max_reached = true;
        }
    }

    public function back()
    {
        switch ($this->step) {
            case 'topics':
                $this->step = 'location';
                break;
            case 'name':
                $this->step = 'topics';
                break;
            case 'describe':
                $this->step = 'name';
                break;
        }
    }

    public function next()
    {
        switch ($this->step) {
            case 'location':
                $this->validateOnly('location_name');
                $this->step = 'topics';
                break;
            case 'topics':
                $this->step = 'name';
                break;
            case 'name':
                $this->validateOnly('group_name');
                $this->step = 'describe';
                break;
        }
    }

    public function store() {
        // Validate description
        $this->validate();

        // Auth User
        $user = auth()->user();

        // Authorize user
        if ($user->cant('create', Group::class) || config('app.is_demo')) {
            session()->flash('alert-danger', __('Action Blocked in demo'));
            return redirect()->route('start.create');
        }

        // Create group
        $group = Group::create([
            'name' => $this->group_name,
            'describe' => clean($this->group_describe),
            'created_by' => $user->id,
            'group_type' => Group::OPEN,
        ]);

        // Attach membership
        $group->memberships()->create([
            'user_id' => $user->id, 
            'membership' => GroupMembership::ORGANIZER,
        ]);

        // Attach address
        $group->setAddress('main', [
            'name' => $this->place_name ?? $this->location_name,
            'address_1' => $this->formatted_address ?? $this->location_name,
            'state' => $this->state,
            'city' => $this->city,
            'country' => $this->country,
            'zip' => $this->postal_code,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ]);

        // Attach topics
        $group->attachTopics(array_column($this->selected_interests, 'id'));

        return redirect()->route('groups.about', ['group' => $group->slug]);
    }

    public function render()
    {
        if ($this->search) {
            $this->recommended_interests = Topic::where('name', 'like', '%'.$this->search.'%')->get()->take(10);
        } 

        return view('application.components.group.start.form');
    }
}
