<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DatePicker extends Component
{
    public $classes;
    public $defaultDate;
    public $minDate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($classes, $defaultDate, $minDate)
    {
        $this->classes = $classes;
        $this->defaultDate = $defaultDate;
        $this->minDate = $minDate ?? 'today';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('application.components.date-picker');
    }
}
