<?php

namespace App\Http\Requests\AssignmentRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
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
            'title'           => [
                'required',
                'string',
                Rule::unique('assignments')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['title', '=', $request->title],
                            ['class_id', '=', $request->class_id],
                            ['id', '<>', $request->id]
                        ]);
                }),
            ],
            'description'       => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'limit_date' => 'required|date',
            'assignment_type_id' => 'required|exists:assignment_types,id',


        ];
    }

    /***
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [


            'title.required' => 'Title is required!',
            'title.unique' => 'Name will be unique',

            'description.required' => 'Description is required!',
            'class_id.required' => 'Class is required!',
            'class_id.unique' => 'Class will be unique',
            'class_id.exists' => 'Class is must exists in classes',
            'limit_date.required'   => 'Limit Date is required!',
        ];
    }
}
