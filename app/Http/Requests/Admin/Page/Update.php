<?php

namespace App\Http\Requests\Admin\Page;

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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:pages,slug,'.$this->page->id,
            'order' => 'integer',
            'is_active' => 'sometimes|boolean',
            'show_on_footer' => 'sometimes|boolean',
        ];
    }
}