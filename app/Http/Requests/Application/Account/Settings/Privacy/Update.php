<?php

namespace App\Http\Requests\Application\Account\Settings\Privacy;

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
            'show_groups_on_profile' => 'nullable|boolean',
            'show_interests_on_profile' => 'nullable|boolean',
        ];
    }
}