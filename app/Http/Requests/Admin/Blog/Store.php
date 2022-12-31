<?php

namespace App\Http\Requests\Admin\Blog;

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
            'name' => 'required|string',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'blog_category_id' => 'required|integer|exists:blog_categories,id',
            'featured_image' => 'required|mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024,
        ];
    }
}