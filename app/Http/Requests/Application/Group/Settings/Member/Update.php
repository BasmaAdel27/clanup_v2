<?php

namespace App\Http\Requests\Application\Group\Settings\Member;

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
            'new_members_need_approved' => 'nullable|boolean',
            'new_members_need_pp' => 'nullable|boolean',
            'allow_members_create_discussion' => 'nullable|boolean',
            'welcome_message' => 'nullable|string|max:255',
        ];
    }
}