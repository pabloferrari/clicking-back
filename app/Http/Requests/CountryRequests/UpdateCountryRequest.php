<?php

namespace App\Http\Requests\CountryRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class UpdateCountryRequest extends FormRequest
{
    use FormValidatorTrait;
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
        #$id = \Request::segment(3);
        return [
            'name' => 'unique:countries,name,' . \Request::instance()->id . '|regex:/^[\pL\s\-]+$/u',
            'code' => 'unique:countries,code,' . \Request::instance()->id . '|regex:/^[\pL\s\-]+$/u',
            #'code' => 'unique:countries,code,' . $id . '|alpha|size:2'
            #'code' => 'unique:countries,code,' . $id . '|alpha|size:2'
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
            'name.unique'   => 'Name will be unique',
            'name.regex'    => 'Name only letters and spaces',
            'code.unique'   => 'Code will be unique',
            'code.alpha'    => 'Code only letters',
            'code.size'     => 'Code length allowed 2',
        ];
    }
}
