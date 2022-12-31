<?php

namespace App\Http\Requests\Application\Account\Settings\Password;

use App\Http\Requests\Rules\MatchOldPassword;
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
            'old_password' => ['required', 'string', 'min:8', new MatchOldPassword],
            'new_password' => 'required|string|confirmed|min:8',
        ];
    }
}