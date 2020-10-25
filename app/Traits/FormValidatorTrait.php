<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * FormValidatorTrait
 */
trait FormValidatorTrait
{

    /**
    * [failedValidation [Overriding the event validator for custom error response]]
    * @param  Validator $validator [description]
    * @return [object][object of various validation errors]
    */
    public function failedValidation(Validator $validator) { 
        //write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json($validator->errors(), 422)); 
    }
}
