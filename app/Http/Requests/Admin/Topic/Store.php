<?php

namespace App\Http\Requests\Admin\Topic;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'name' => 'required|string|max:255',
            'topic_category_id' => 'required|integer|exists:topic_categories,id',
        ];
    }
}