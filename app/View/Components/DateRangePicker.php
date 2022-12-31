<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DateRangePicker extends Component
{
    public $classes;
    public $defaultDateFrom;
    public $defaultDateTo;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($classes, $defaultDateFrom, $defaultDateTo)
    {
        $this->classes = $classes;
        $this->defaultDateFrom = $defaultDateFrom;
        $this->defaultDateTo = $defaultDateTo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('application.components.date-range-picker');
    }
}
