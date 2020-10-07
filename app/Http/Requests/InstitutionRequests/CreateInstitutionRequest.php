<?php

namespace App\Http\Requests\InstitutionRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class CreateInstitutionRequest extends FormRequest
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
            'name' => 'required|unique:institutions|string',
            'email' => 'required|unique:institutions|email',
            'phone' => 'required|regex:/(01)[0-9]{9}/',
            'cuit' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            'active' => 'nullable|boolean',
            'plan_id' => 'required|exists:plans,id',
            'city_id' => 'required|exists:cities,id',
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
            'name.required' => 'Name is required!',
            'name.unique' => 'Name will be unique',
            'email.required' => 'email is required!',
            'email.unique' => 'email will be unique',
            'email.email' => 'email must be valid',
            'phone.regex' => 'phone must be valid',
            'cuit.string' => 'cuit is string!',
            'image.image' => 'image format must be valid',
            'active.boolean' => 'active is boolean',
            'plan_id.required' => 'plan id is required!',
            'plan_id.exists' => 'plan id must exist in plans',
            'city_id.required' => 'city id is required!',
            'city_id.exists' => 'city id must exist in cities',
        ];
    }
}
