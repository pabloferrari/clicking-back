<?php

namespace App\Http\Requests\CourseRequests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class CreateCourseStudentRequest extends FormRequest
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
        $classroom = Classroom::where('institution_id', Auth::user()->institution_id)->with('courses')
            ->whereHas('courses', function ($query) use ($request) {
                $query->where('id', $request->course_id);
            })->first();
        $classroomId = $classroom->id;


        return [
            // 'student_id'     => 'required|unique:classroom_students|exists:students,id',
            // 'classroom_id'     => 'required|unique:classroom_students,classroom_id,' . $classroomId . '|exists:classrooms,id',
            // 'classroom_id' =>  [
            //     'required',
            //     foreach ($variable as $key => $value) {
            //         # code...
            //     }
            //     Rule::unique('classroom_students')->where(function ($query) use ($request, $classroomId) {
            //         return $query
            //             ->where([
            //                 ['student_id',     '=', $request->student_id],
            //                 ['classroom_id',     '=', 18]

            //             ]);
            //     }),
            // ],

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
            'student_id.required'     => 'Student is required!',
            'student_id.unique'       => 'Student will be unique',

            // 'classroom_id.required'   => 'Classroom is required!',
            // 'classroom_id.unique'     => 'Classroom will be unique',

        ];
    }
}
