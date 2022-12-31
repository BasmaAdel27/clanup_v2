<?php

namespace App\Http\Requests\Application\Group\Settings\Basic;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */ 
    public function rules()
    {
        return [
            'group_name' => 'required|max:120',
            'group_describe' => 'required|max:5000',
            'location_name' => 'required|max:255',
        ];
    }
}