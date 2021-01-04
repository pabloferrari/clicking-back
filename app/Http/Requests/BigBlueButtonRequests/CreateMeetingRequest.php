<?php

namespace App\Http\Requests\BigBlueButtonRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\FormValidatorTrait;
use App\Models\{MeetingType,MeetingRequest,Classroom,IntitutionClass,Teacher,Student,User};
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
            'meeting_type'  =>  'required|integer|exists:bbb_meeting_types,id',
            'model' =>  'required|string',
            'model_id' =>  'nullable|integer',
            'ids' =>  'nullable|array',
            'minutes' =>  'nullable|integer',
            'title' =>  'required|string'
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
            'model.required' => 'Model is required!',
            'title.string' => 'Title is string!',
            'meeting_type.required' => 'meeting_type is required!'
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
            $meetingType = MeetingType::findOrFail($this->input('meeting_type'));
            
            // VALIDO EL ROLE DEL TIPO CON EL DEL USUARIO
            if(!$user->hasRole($meetingType->role)) {
                $validator->errors()->add('meeting_type', 'meeting_type is invalid!');
            }

            switch ($this->input('model')) {
                case 'classroom':

                    if(!$this->input('model_id')) {
                        $validator->errors()->add('model_id', 'model_id is required for meeting_type: ' . $meetingType->type);
                    } else {
                        $classRoom = Classroom::where('id', $this->input('model_id'))->where('institution_id', $user->institution_id)->first();
                        if(!$classRoom) {
                            $validator->errors()->add('model_id', 'model_id is invalid!' . $user->institution_id);
                        }
                    }
                    
                    break;
                case 'class':
                    break;
                    
                case 'user':
                    // VALIDO EL ID
                    dd(__METHOD__, $this->input('users'));
                    break;

                case 'teacher':
                    // VALIDO EL ID
                    dd(__METHOD__, $this->input('teachers'));
                    break;
                case 'students':
                    // VALIDO EL ID
                    dd(__METHOD__, $this->input('students'));
                    break;
                default:
                    // $validator->errors()->add('model_id', 'model_id is required for meeting_type: ' . $meetingType->type);
                    $validator->errors()->add('model', 'model is invalid!');
                    break;
            }

            if(count($validator->errors()) === 0){

                // VALIDO QUE NO SE HAYA CREADO LA MISMA CLASE
                $exist = MeetingRequest::where('model', $this->input('model'))
                ->where('user_id', Auth::user()->id)
                ->where('meeting_type', $this->input('meeting_type'))
                ->where('title', $this->input('title'))
                ->where('created', 0)->first();
                
                if($exist) {
                    // $validator->errors()->add('meeting-request', 'meeting-request already exists!');
                }
            }

            
        }
    }

}
