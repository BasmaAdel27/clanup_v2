<?php

namespace App\Http\Requests\Application\Group\Settings\Optional;

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
            'facebook' => 'nullable|string|max:50',
            'instagram' => 'nullable|string|max:50',
            'twitter' => 'nullable|string|max:50',
            'linkedin' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:50',
        ];
    }
}