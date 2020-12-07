<?php

namespace App\Http\Requests\CourseClassRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateCourseClassRequest extends FormRequest
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
            #'title' => 'required|unique:classes|string',
            'title' =>  [
                'required',
                'string',
                Rule::unique('classes')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['title', '=', $request->title],
                            ['course_id', '=', $request->course_id]
                        ]);
                }),
            ],
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',

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
            'title.required' => 'Title is required!',
            'title.unique' => 'Title will be unique',
            'description.string' => 'description is string!',
            'course_id.required' => 'course id is required!',
            'course_id.exists' => 'course id must exist in courses',

        ];
    }
}
