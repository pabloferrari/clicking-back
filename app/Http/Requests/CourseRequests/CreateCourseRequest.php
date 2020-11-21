<?php

namespace App\Http\Requests\CourseRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\{Subject,Teacher,Classroom,CourseType,Course};
use Illuminate\Support\Facades\Auth;

class CreateCourseRequest extends FormRequest
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
                            ['course_type_id', '=', $request->course_type_id]
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

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $this->after($validator);
        });
    }

    public function after($validator)
    {
        if(count($validator->errors()) === 0){

            if($this->input('subject_id')) {
                if(!Subject::where('id', $this->input('subject_id'))->where('institution_id', Auth::user()->institution_id)->first())
                $validator->errors()->add('subject_id', 'subject_id is invalid!');
            }

            if($this->input('teacher_id')) {
                if(!Teacher::where('id', $this->input('teacher_id'))->whereHas('user', function ($query) {
                    return $query->where('institution_id', Auth::user()->institution_id);
                })->first())
                $validator->errors()->add('teacher_id', 'teacher_id is invalid!');
            }

            if($this->input('classroom_id')) {
                if(!Classroom::where('id', $this->input('classroom_id'))->where('institution_id', Auth::user()->institution_id)->first())
                $validator->errors()->add('classroom_id', 'classroom_id is invalid!');
            }

            if($this->input('course_type_id')) {
                if(!CourseType::where('id', $this->input('course_type_id'))->where('institution_id', Auth::user()->institution_id)->first())
                $validator->errors()->add('course_type_id', 'course_type_id is invalid!');
            }

            if(Course::where('subject_id', $this->input('subject_id'))
            ->where('teacher_id', $this->input('teacher_id'))
            ->where('classroom_id', $this->input('classroom_id'))
            ->where('course_type_id', $this->input('course_type_id'))
            ->first())
            $validator->errors()->add('course', 'A course already exists!');

        }
    }
}
