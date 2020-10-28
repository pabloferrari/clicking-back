<?php

namespace App\Http\Requests\AssignmentTypeRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateAssignmentTypeRequest extends FormRequest
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
            'name' => 'required|unique:assignment_types,name,' . $request->get('id') . '|string',
            'group_enabled' => 'boolean',
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
            'name.unique' => 'Name will be unique'
        ];
    }
}
