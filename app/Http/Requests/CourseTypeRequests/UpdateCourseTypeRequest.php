<?php

namespace App\Http\Requests\CourseTypeRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateCourseTypeRequest extends FormRequest
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
                Rule::unique('course_types')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['name', '=', $request->name],
                            ['institution_id', '=', $request->institution_id],
                            ['id', '<>', $request->get('id')]
                        ]);
                }),
            ],
            'institution_id' => 'required|exists:institutions,id',
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
            'institution_id.required' => 'institution id is required!',
            'institution_id.exists'   => 'institution id must exist in institutions',
        ];
    }
}
