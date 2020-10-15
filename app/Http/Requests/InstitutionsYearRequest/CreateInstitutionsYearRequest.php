<?php

namespace App\Http\Requests\InstitutionsYearRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CreateInstitutionsYearRequest extends FormRequest
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
            // 'year'           => 'required|unique:institutions_years|string',
            'year' =>  [
                'required',
                'string',
                Rule::unique('institutions_years')->where(function ($query) use ($request) {
                    return $query
                        ->where([
                            ['year','=',$request->year],
                            ['institution_id','=', $request->institution_id]
                        ]);
                }),
            ],

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
            'year.required' => 'Year is required!',
            'year.string' => 'year is string!',
            'year.unique' => 'Year will be unique',
            'institution_id.required' => 'institution id is required!',
            'institution_id.exists' => 'institution id must exist in intitutions',
        ];
    }
}
