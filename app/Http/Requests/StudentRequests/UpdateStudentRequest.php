<?php

namespace App\Http\Requests\StudentRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                Rule::unique('students')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['name', '=', $request->name],
                            ['id', '<>', $request->get('id')]
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
            'name.unique' => 'Name is unique!',
        ];
    }
}
