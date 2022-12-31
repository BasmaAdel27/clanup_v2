<?php

namespace App\Http\Requests\Application\Account\Settings\Details;

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
        $user = auth()->user();

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable',
            'bio' => 'nullable|string|max:500',
            'timezone' => 'required|string',
            'username' => 'required|string|max:30|unique:users,username,' . $user->id,
            'profile_picture' => 'nullable|mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024
        ];
    }
}