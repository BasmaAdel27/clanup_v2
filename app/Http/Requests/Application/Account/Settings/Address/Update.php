<?php

namespace App\Http\Requests\Application\Account\Settings\Address;

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
            'city' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'hometown' => 'nullable|string|max:50',
        ];
    }
}