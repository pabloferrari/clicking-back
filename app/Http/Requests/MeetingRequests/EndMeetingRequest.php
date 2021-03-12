<?php

namespace App\Http\Requests\MeetingRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\FormValidatorTrait;
use App\Models\{MeetingType,Meeting,MeetingRequest,Classroom,IntitutionClass,Teacher,Student,User};
use Log;

class EndMeetingRequest extends FormRequest
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
            'meetingId'  =>  'required|exists:meetings,id'
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
            'meetingId.required' => 'MeetingId is required!'
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
            // VALIDO QUE TENGA PERMISOS PARA FINALIZAR LA MEETING
            // DEBE SER EL MODERADOR O TENER PERMISOS DEL INSTITUION O SUPERIOR
            $user = Auth::user();
            $meeting = Meeting::where('id',$this->input('meetingId'))->first();
            $moderatorId = $meeting->user_id;
            $institutionId = $meeting->institution_id;
            
            if($meeting->user_id !== $user->id) {

                if($institutionId !== $user->institution_id){

                    $validator->errors()->add('meetingId', 'meetingId you don\'t have permissions to end this meeting');

                } else {

                    if(!$user->hasRole('institution') && !$user->hasRole('admin') && !$user->hasRole('root')) {
                        $validator->errors()->add('meetingId', 'meetingId you don\'t have permissions to end this meeting');
                    }

                }

            }
            
        }
    }

}
