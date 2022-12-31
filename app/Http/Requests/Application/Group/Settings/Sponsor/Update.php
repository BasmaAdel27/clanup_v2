<?php

namespace App\Http\Requests\Application\Group\Settings\Sponsor;

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
            'name' => 'required|string|max:120',
            'description' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024
        ];
    }
}