<?php

namespace App\Http\Requests\CourseRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'course_type_id' =>  [
                'required',
                'string',
                Rule::unique('courses')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['subject_id',     '=', $request->subject_id],
                            ['teacher_id',     '=', $request->teacher_id],
                            ['classroom_id',   '=', $request->classroom_id],
                            ['course_type_id', '=', $request->course_type_id],
                            ['id',            '<>', $request->get('id')]
                        ]);
                }),
            ],
            'subject_id'     => 'required|exists:subjects,id',
            'teacher_id'     => 'required|exists:teachers,id',
            'classroom_id'   => 'required|exists:classrooms,id',
            'course_type_id' => 'required|exists:course_types,id'
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
            'subject_id.required'     => 'Subject is required!',
            'subject_id.unique'       => 'Subject will be unique',
            'teacher_id.required'     => 'Teacher is required!',
            'teacher_id.unique'       => 'Teacher will be unique',
            'classroom_id.required'   => 'Classroom is required!',
            'classroom_id.unique'     => 'Classroom will be unique',
            'course_type_id.required' => 'Course Type is required!',
            'course_type_id.unique'   => 'Course Type will be unique',
        ];
    }
}
