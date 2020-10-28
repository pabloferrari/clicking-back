<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;
use Illuminate\Support\Facades\Auth;
use Hash;

class RessetPasswordRequest extends FormRequest
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
            'password' => 'required|string',
            'new-password' => 'required|string',
            'new-password-validate' => 'required|string',
        ];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $this->after($validator);
        });
    }

    public function after($validator)
    {

        if(count($validator->errors()) === 0){

            if($this->input('new-password') !== $this->input('new-password-validate')){
                $validator->errors()->add('new-password-validate', 'Invalid new password validation');
            }

            $password = $this->input('password');
            $hashedPassword = Auth::user()->password;  // Taking the value from database

            if(Hash::check($password, $hashedPassword))
            {
                // OK
            }
            else
            {
                $validator->errors()->add('password', 'Invalid current password');
            }

        }
    }
}
