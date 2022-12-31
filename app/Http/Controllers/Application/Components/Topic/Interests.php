<?php

namespace App\Http\Controllers\Application\Components\Topic;

use Livewire\Component;
use App\Models\Topic;

class Interests extends Component
{
    public $model;
    public $selected_interests;
    public $recommended;
    public $search;
    public $selected_topics_max_reached = false;

    protected $listeners = ['refreshComponent' => 'refresh'];

    public function mount()
    {
        $this->selected_interests = $this->model->topics;
        $this->recommended = Topic::where('name', 'like', '%'.$this->search.'%')->inRandomOrder()->limit(10)->get();
        if (count($this->selected_interests) >= 15) {
            $this->selected_topics_max_reached = true;
        }
    }

    public function refresh()
    {
        $this->selected_interests = $this->model->topics()->get();
    }

    public function detach($id)
    {
        if ($this->model->hasAnyTopics($id)) {
            $this->model->detachTopics($id);
        }

        if (count($this->selected_interests) < 15) {
            $this->selected_topics_max_reached = false;
        }

        $this->emit('refreshComponent');
    }

    public function attach($id)
    {
        $selected_topics_count = count($this->selected_interests) + 1;

        if (!$this->model->hasAnyTopics($id) && $selected_topics_count <= 15) {
            $this->model->attachTopics($id);
        }

        if ($selected_topics_count == 15) {
            $this->selected_topics_max_reached = true;
        }

        $this->emit('refreshComponent');
    }

    public function render()
    {
        if ($this->search) {
            $this->recommended = Topic::where('name', 'like', '%'.$this->search.'%')->get()->take(10);
        } 

        return view('application.components.topic.interests', [
            'recommended' => $this->recommended
        ]);
    }
}