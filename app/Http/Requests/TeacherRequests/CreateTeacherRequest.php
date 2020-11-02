<?php

namespace App\Http\Requests\TeacherRequests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
class CreateTeacherRequest extends FormRequest
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
            'name'  =>  'required|string',
            'phone' =>  'nullable|regex:/([0-9])[0-9]{9}/',
            'email' =>  'required|unique:users|email',
            'phone' =>  'required|string',
            'description' =>  'required',
            'password' => 'required|string',
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
            'name.string' => 'Name is string!',
            'email.required' => 'email is required!',
            'email.unique' => 'email will be unique',
            'email.email' => 'email must be valid',
            'phone.regex' => 'phone must be valid',
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

            if(!$this->input('institution_id')) {
                
                if(Auth::user()->institution_id === null)
                $validator->errors()->add('institution_id', 'institution_id is required!');

            } else {

                if(!Auth::user()->hasRole('admin') || Auth::user()->institution_id != $this->input('institution_id')){
                    $validator->errors()->add('institution_id', 'institution_id is invalid!' . Auth::user()->institution_id);
                }

            }
            
        }
    }
}
