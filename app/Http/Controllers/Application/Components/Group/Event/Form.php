<?php

namespace App\Http\Controllers\Application\Components\Group\Event;

use App\Http\Requests\Rules\AfterTime;
use App\Http\Requests\Rules\BeforeTime;
use App\Models\Address;
use App\Models\Event;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    // Component props
    public $group;
    public $event;
    public $optional_setting_rsvp_question = false;
    public $optional_setting_allowed_guest = false;
    public $optional_setting_event_fee = false;
    public $minDate = 'today';

    // Event rules
    protected function rules()
    {
        $featured_photo_file = isset($this->event['id']) ? 'sometimes|' : 'required|';
        return [
            'event.title' => 'required|max:90',
            'event.start_date' => 'required|date_format:Y-m-d|after_or_equal:today|before_or_equal:event.end_date',
            'event.start_time' => ['required', 'date_format:H:i', new BeforeTime($this->event['start_date'], $this->event['end_date'], $this->event['end_time'])],
            'event.end_date' => 'required|date_format:Y-m-d|after_or_equal:event.start_date',
            'event.end_time' => ['required', 'date_format:H:i', new AfterTime($this->event['start_date'], $this->event['end_date'], $this->event['start_time'])],
            'event.featured_photo_file' => $featured_photo_file . 'image|mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024,
            'event.description' => 'required|string|max:5000',
            'event.is_online' => 'sometimes|boolean',
            'event.online_meeting_link' => 'required_if:event.is_online,true',
            'event.address.name' => 'required_if:event.is_online,false|max:255',
            'event.address.location_name' => 'nullable|max:255',
            'event.address.address_1' => 'nullable|max:255',
            'event.address.lat' => 'nullable|max:12',
            'event.address.lng' => 'nullable|max:12',
            'event.address.country' => 'nullable|max:255',
            'event.address.state' => 'nullable|max:255',
            'event.address.city' => 'nullable|max:255',
            'event.address.zip' => 'nullable|max:255',
            'event.how_to_find_us' => 'nullable|string|max:255',
            'event.rsvp_question' => 'required_if:optional_setting_rsvp_question,true|string|nullable|max:255',
            'event.allowed_guests' => 'required_if:optional_setting_allowed_guest,true|integer|nullable',
            'event.fee_method' => 'required_if:optional_setting_event_fee,true|nullable',
            'event.fee_currency_id' => 'required_if:optional_setting_event_fee,true|exists:currencies,id|nullable',
            'event.fee_amount' => 'required_if:optional_setting_event_fee,true|numeric|nullable',
            'event.fee_additional_refund_policy' => 'nullable|string|max:255',
        ];
    }

    // Validate each input on change
    public function updated($name)
    {
        $date_array = ['event.start_date', 'event.end_date', 'event.start_time', 'event.end_time'];
        if (in_array($name, $date_array)) {
            foreach ($date_array as $key) {
                $this->validateOnly($key);
            }
        } else {
            $this->validateOnly($name);
        }
    }

    // Mount variables
    public function mount()
    {
        if (isset($this->event->id)) {
            $this->event = $this->event->append('address')->toArray();
            $this->optional_setting_rsvp_question = $this->event['rsvp_question'] ? true : false;
            $this->optional_setting_allowed_guest = $this->event['allowed_guests'] ? true : false;
            $this->optional_setting_event_fee = $this->event['fee_amount'] && $this->event['fee_amount'] != "0.00" ? true : false;
        } else {
            $event = new Event();
            $event->image = null;
            $event->is_online = false;
            $event->address = new Address();
            $this->event = $event->toArray();
        }
    }

    // Populate data
    private function populate_data($status)
    {
        // Validate Input
        $this->validate();

        // Authenticated User
        $user = auth()->user();
        $event = $this->event;

        // Attach address if exists
        $event_addrress = null;
        if (isset($event['is_online']) && $event['is_online'] == false) {
            $address = $event['address'];
            $event_addrress = [
                'name' => isset($address['location_name']) ? $address['location_name'] : $address['name'],
                'address_1' => isset($address['address_1']) ? $address['address_1'] : null,
                'lat' => isset($address['lat']) ? $address['lat'] : null,
                'lng' => isset($address['lng']) ? $address['lng'] : null,
                'city' => isset($address['city']) ? $address['city'] : null,
                'state' => isset($address['state']) ? $address['state'] : null,
                'country' => isset($address['country']) ? $address['country'] : null,
                'zip' => isset($address['zip']) ? $address['zip'] : null,
            ];
        }
        $event_array = [
            'group_id' => $this->group->id,
            'created_by' => $user->id,
            'title' => $event['title'],
            'description' => clean($event['description']),
            'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $event['start_date'] . ' ' . $event['start_time'], $user->timezone)->timezone('UTC')->format('Y-m-d H:i:s'),
            'ends_at' => Carbon::createFromFormat('Y-m-d H:i', $event['end_date'] . ' ' . $event['end_time'], $user->timezone)->timezone('UTC')->format('Y-m-d H:i:s'),
            'is_online' => isset($event['is_online']) && $event['is_online'] == true ? true : false,
            'online_meeting_link' => $event['online_meeting_link'] ?? null,
            'status' => $status == 'publish' ? Event::PUBLISHED : Event::DRAFT,
            'how_to_find_us' => isset($event['how_to_find_us']) ? $event['how_to_find_us'] : null,
            'address' => $event_addrress,
        ];

        if ($this->optional_setting_allowed_guest) {
            $event_array['allowed_guests'] = isset($event['allowed_guests']) ? $event['allowed_guests'] : null;
        } else {
            $event_array['allowed_guests'] = null;
        }

        if ($this->optional_setting_rsvp_question) {
            $event_array['rsvp_question'] = isset($event['rsvp_question']) ? $event['rsvp_question'] : null;
        } else {
            $event_array['rsvp_question'] = null;
        }

        if ($this->optional_setting_event_fee) {
            $event_array['fee_method'] = isset($event['fee_method']) ? $event['fee_method'] : null;
            $event_array['fee_currency_id'] = isset($event['fee_currency_id']) ? $event['fee_currency_id'] : null;
            $event_array['fee_amount'] = isset($event['fee_amount']) ? $event['fee_amount'] : null;
            $event_array['fee_additional_refund_policy'] = isset($event['fee_additional_refund_policy']) ? $event['fee_additional_refund_policy'] : null;
        } else {
            $event_array['fee_method'] = null;
            $event_array['fee_currency_id'] = null;
            $event_array['fee_amount'] = null;
            $event_array['fee_additional_refund_policy'] = null;
        }

        return $event_array;
    }

    // Save event to database
    public function store($status) {
        // Authorize user
        if (auth()->user()->cant('create', [Event::class, $this->group]) || config('app.is_demo')) {
            session()->flash('alert-danger', __('Action Blocked in demo'));
            redirect()->route('groups.events', ['group' => $this->group->slug]);
        }

        // Get data
        $data = $this->populate_data($status);

        // Create Event
        $event = Event::create($data);

        // Upload Featured Photo
        if (isset($this->event['featured_photo_file'])) {
            $event->addMedia($this->event['featured_photo_file']->getRealPath())->toMediaCollection();
        }

        // Attach address if exists
        if ($data['address']) {
            $event->setAddress('main', $data['address']);
        }

        return redirect()->route('groups.events.show', ['group' => $this->group->slug, 'event' => $event->uid]);
    }

    // Save event to database
    public function update($status) {
        // Find event
        $event = Event::findByUid($this->event['uid']);

        // Authorize user
        if (auth()->user()->cant('update', $event) || config('app.is_demo')) {
            session()->flash('alert-danger', __('Action Blocked in demo'));
            redirect()->route('groups.events.show', ['group' => $this->group->slug, 'event' => $event->uid]);
        }

        // Get data
        $data = $this->populate_data($status);

        // Update Event
        $event->update($data);

        // Upload Featured Photo
        if (isset($this->event['featured_photo_file']) && !config('app.is_demo')) {
            $event->addMedia($this->event['featured_photo_file']->getRealPath())->toMediaCollection();
        }

        // Attach address if exists
        if ($data['address']) {
            $event->updateAddress('main', $data['address']);
        }

        return redirect()->route('groups.events.show', ['group' => $this->group->slug, 'event' => $event->uid]);
    }

    public function render()
    {
        return view('application.components.group.event.form');
    }
}
