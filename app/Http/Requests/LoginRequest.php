<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class LoginRequest extends FormRequest
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
        return [
            'email'       => 'required|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
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
            'email.required' => 'Email is required!',
            'name.email' => 'Email must be email!',
            'password.required' => 'Password is required!'
        ];
    }
    

    
}
