<?php

namespace App\Http\Requests\AssignmentGroupRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateAssignmentGroupRequest extends FormRequest
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
            'classroom_student_id' =>  'required|exists:classroom_students,id',
            'assignment_id'        =>  'required|exists:assignments,id',
            'num'                  => 'required|number'
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
            'classroom_student_id.required' => 'Class Room Students is required!',
            'classroom_student_id.unique' => 'Class Room Student will be unique',
            'classroom_student_id.exists'   => 'Class Room Student must exist in Classes Rooms Students',

            'assignment_id.required' => 'Assignment is required!',
            'assignment_id.unique' => 'Assignment will be unique',
            'assignment_id.exists'   => 'Assignment must exist in Assignments',
        ];
    }
}
