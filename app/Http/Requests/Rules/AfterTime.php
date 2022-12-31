<?php

namespace App\Http\Requests\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AfterTime implements Rule
{
    public $starts_date;
    public $ends_date;
    public $after_time;

    public function __construct($starts_date, $ends_date, $after_time) {
        $this->starts_date = $starts_date;
        $this->ends_date = $ends_date;
        $this->after_time = $after_time;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the starts_date and ends_date are the same
        if (Carbon::createFromFormat('Y-m-d', $this->starts_date)->eq(Carbon::createFromFormat('Y-m-d', $this->ends_date))) {
            return Carbon::createFromFormat('H:i', $this->after_time)->lt(Carbon::createFromFormat('H:i', $value));
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be a date after :value.');
    }
}
