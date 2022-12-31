<?php

namespace App\Http\Requests\Admin\Plan;

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
            'description' => 'nullable|string',
            'price' => 'required|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'yearly_price' => 'required|regex:/^(?=.*?[0-9])[0-9.,]+$/',
            'trial_period' => 'sometimes|integer|min:0',
            'order' => 'integer',
            'paypal_plan_id' => 'nullable|string',
            'paypal_yearly_plan_id' => 'nullable|string',
            'features.groups' => 'required|integer',
            'features.can_access_communication_tools' => 'sometimes|boolean',
            'features.can_access_email_addresses' => 'sometimes|boolean',
            'features.can_access_custom_reports' => 'sometimes|boolean',
            'features.can_display_sponsors' => 'sometimes|boolean',
            'features.max_sponsors_count' => 'required|integer',
        ];
    }
}