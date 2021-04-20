<?php

namespace App\Http\Requests\SubjectRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;

class UpdateSubjectRequest extends FormRequest
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
                Rule::unique('subjects')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['name','=',$request->name],
                            ['institution_id','=', $request->institution_id],
                            ['id','<>', $request->get('id') ]
                        ]);
                }),
            ]
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

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $this->after($validator);
        });
    }

    public function after($validator)
    {
        if(count($validator->errors()) === 0){
            $user = Auth::user();
            if($this->input('institution_id')) {
                if($user->institution_id != $this->input('institution_id'))
                $validator->errors()->add('id', 'Id incorrecto!');
            } else {
                $subject = Subject::where('id', $this->input('id'))->first();
                if($subject->institution_id != $user->institution_id)
                $validator->errors()->add('id', 'Id incorrecto!');
            }
        }
    }
}
