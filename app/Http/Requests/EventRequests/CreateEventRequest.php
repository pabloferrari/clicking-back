<?php

namespace App\Http\Requests\EventRequests;


use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormValidatorTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Classes\UserService;
use Log;

class CreateEventRequest extends FormRequest
{

    use FormValidatorTrait;
    public $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }


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
            'title'  =>  'required|string',
            'event_type' => 'required|exists:App\Models\EventType,id',
            'notes' => 'nullable|string',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i',
            'guests' => 'nullable|array',
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
            'title.required' => 'El titulo es requerido',
            'event_type.required' => 'El tipo de evento es requerido',
            'event_type.exists' => 'El id del tipo de evento es invalido',
            'start_date.required' => 'La fecha de inicio es requerida',
            'end_date.required' => 'La fecha de final es requerida',
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
        if($this->input('guests')) {
            try {
                $users = $this->userService->getUsersByIds($this->input('guests'));
                if(count($users) !== count($this->input('guests'))){
                    $validator->errors()->add('guests', 'Invalid id: ' . json_encode($this->input('guests')));
                }
            } catch (\Throwable $th) {
                Log::error(__METHOD__ . ' ' . $th->getMessage() . ' guests ' . json_encode($this->input('guests')));
                $validator->errors()->add('guests', 'Invalid id ' . json_encode($this->input('guests')));
            }
        }
    }
}
