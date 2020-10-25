<?php

namespace App\Http\Requests\ProvinceRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class CreateProvinceRequest extends FormRequest
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
            'name'       => 'required|unique:provinces|string',
            'iso31662'   => 'required|unique:provinces|string',
            'country_id' => 'required|exists:countries,id'
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
            'name.required'       => 'Name is required!',
            'name.unique'         => 'Name will be unique',
            'country_id.required' => 'country id is required!',
            'country_id.exists'   => 'country id must exist in countries',
            'iso31662.required'   => 'iso31662 is required!',
            'iso31662.unique'     => 'iso31662 is unique!',
        ];
    }
}
