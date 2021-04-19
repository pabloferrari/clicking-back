<?php

namespace App\Http\Requests\TeacherRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\{Teacher, User};

class UpdateTeacherRequest extends FormRequest
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
            'name' =>  [
                'nullable',
                'string',
            ],
            'id' =>  [
                'required'
            ],
            'email' => [
                'nullable',
                'email'
            ],
            'phone' =>  'nullable|string',
            'password' => 'nullable|string'
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

            $teacher = Teacher::with('user')->where('id', $this->input('id'))->first();

            if(!$this->input('institution_id')) {
                
                if(Auth::user()->institution_id === null)
                $validator->errors()->add('institution_id', 'institution_id is required!');

                if($teacher->user->institution_id !== Auth::user()->institution_id) {
                    $validator->errors()->add('id', 'No tienes permisos para modificar este usuario.');
                }

            } else {

                if(!Auth::user()->hasRole('admin') && Auth::user()->institution_id != $this->input('institution_id')){
                    $validator->errors()->add('institution_id', 'institution_id is invalid!' . Auth::user()->institution_id);
                }

            }

            if($this->input('email')) {

                if($this->input('email') != $teacher->email) {
                    $existsEmail = User::where('email', $this->input('email'))->first();

                    if($existsEmail && $existsEmail->id != $teacher->user->id)
                    $validator->errors()->add('email', 'El email esta en uso');

                }
                
            }
            
        }
    }
}
