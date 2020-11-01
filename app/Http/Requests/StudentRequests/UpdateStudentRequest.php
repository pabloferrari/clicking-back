<?php

namespace App\Http\Requests\StudentRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateStudentRequest extends FormRequest
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
                'required',
                'string',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('students')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['email', '=', $request->email],
                            ['id', '<>', $request->get('id')],

                        ]);
                }),
            ],
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


        ];
    }
}
