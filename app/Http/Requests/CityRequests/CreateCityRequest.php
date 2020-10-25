<?php

namespace App\Http\Requests\CityRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class CreateCityRequest extends FormRequest
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
            'name' => 'required|unique:cities|string',
            'province_id' => 'required|exists:provinces,id',
            'zip_code' => 'required|unique:cities|regex:/\b\d{4}\b/'
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
            'province_id.required' => 'province id is required!',
            'province_id.exists' => 'province id must exist in provinces',
            'zip_code.required' => 'zip code is required!',
            'zip_code.unique' => 'zip code will be unique',
            'zip_code.regex' => 'zip code is 5 digits long'
        ];
    }
}
