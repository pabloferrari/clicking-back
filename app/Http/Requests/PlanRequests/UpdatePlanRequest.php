<?php

namespace App\Http\Requests\PlanRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;
use Illuminate\Http\Request;

class UpdatePlanRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'unique:plans,id'. $request->get('id') .'|string',
            'active' => 'boolean',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Name will be unique'
        ];
    }

}
