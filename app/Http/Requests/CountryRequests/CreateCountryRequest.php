<?php

namespace App\Http\Requests\CountryRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class CreateCountryRequest extends FormRequest
{
    // use FormValidatorTrait;
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
            'name' => 'required|unique:countries|regex:/^[\pL\s\-]+$/u',
            'code' => 'required|unique:countries|alpha|size:2'
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
            'name.unique'   => 'Name will be unique',
            'name.regex'    => 'Name only letters and spaces',
            'code.required' => 'Code is required!',
            'code.unique'   => 'Code will be unique',
            'code.alpha'    => 'Code only letters',
            'code.size'     => 'Code length allowed 2',
        ];
    }
}
