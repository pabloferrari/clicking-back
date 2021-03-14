<?php

namespace App\Http\Requests\ClassroomRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateClassroomRequest extends FormRequest
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
            'name'           => 'required|string',
            'shift_id'       => 'required|exists:shifts,id',
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
            'name.required'           => 'Name is required!',
            'name.unique'             => 'Name will be unique',
            'shift_id.required'       => 'shift id is required!',
            'shift_id.exists'         => 'shift id must exist in shifts',
            'institution_id.required' => 'institution id is required!',
            'institution_id.exists'   => 'institution id must exist in institutions',
        ];
    }
}
