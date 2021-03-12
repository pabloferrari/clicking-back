<?php

namespace App\Http\Requests\MeetingRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\FormValidatorTrait;
use App\Models\{Meeting};
use Log;

class CreateMeetingRequest extends FormRequest
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
            'model' =>  'required|string',
            'model_id' =>  'nullable|integer',
            'ids' =>  'nullable|array',
            'minutes' =>  'nullable|integer',
            'link' =>  'required|string'
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
            'link.required' => 'El Link es requerido!',
            'model.required' => 'El modelo es requerido!',
            'model_id.required' => 'model_id es requerido!'
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
            $meeting = Meeting::where('user_id', $user->id)->where('finished', false)->where('model', $this->input('model'))->where('model_id', $this->input('model_id'))->first();
            if($meeting) {
                $validator->errors()->add('meeting', 'La meeting ya fue creada: ' . $meeting->link);
            } 
        }
    }

}
