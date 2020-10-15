<?php

namespace App\Http\Requests\CommissionRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class UpdateCommissionRequest extends FormRequest
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
                Rule::unique('commissions')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['name','=',$request->name],
                            ['turn_id','=', $request->turn_id],
                            ['institution_year_id','=', $request->institution_year_id],
                            ['id','<>', $request->get('id')],
                        ]);
                }),
            ],
            'turn_id'             => 'required|exists:turns,id',
            'institution_year_id' => 'required|exists:institutions_years,id',

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
            'name.unique' => 'Name will be unique',
            'turn_id.required' => 'turn is required!',
            'institution_year_id.required' => 'instutitution year is required',
            'institution_year_id.exists'   => 'instutitution year must exist in intitutions',
            'turn_id.exists' => 'turn must exist in intitutions',
        ];
    }
}
