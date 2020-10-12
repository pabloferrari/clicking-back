<?php

namespace App\Http\Requests\TeacherRequests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CreateTeacherRequest extends FormRequest
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
            ],
            'phone'   => 'nullable|regex:/([0-9])[0-9]{9}/',
            'email'   => 'required|unique:teachers|email',
            // 'turn_id' => 'required|exists:turns,id',
            // 'turns' => [
            //     'required',
            //     Rule::unique('teachers_turns')->where(function ($query) use ($request) {
            //         return $query
            //             ->where([
            //                 ['turn_id','=',$request->turns],

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
            'name.required' => 'Name is required!',
            'name.string' => 'Name is string!',
            'email.required' => 'email is required!',
            'email.unique' => 'email will be unique',
            'email.email' => 'email must be valid',
            'phone.regex' => 'phone must be valid',
            'turn_id.required' => 'turn is required!',
            'turn_id.exists' => 'turn  must exist in tuns',
        ];
    }
}
