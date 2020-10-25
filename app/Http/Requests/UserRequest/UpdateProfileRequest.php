<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;

class UpdateProfileRequest extends FormRequest
{
    use FormValidatorTrait;
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
    public function rules()
    {
        return [
            'email' => 'required|unique:users|email',
            'name' => 'required|string',
            'password' => 'required|string',
            'description' => 'nullable|string',
            'institution_id' => 'required|exists:App\Models\Institution,id',
            'images' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}