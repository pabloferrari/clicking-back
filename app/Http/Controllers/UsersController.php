<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest\{CreateAdminRequest,UpdateProfileRequest, RessetPasswordRequest};
use App\Classes\{UserService,Helpers};
use Log;

class UsersController extends Controller
{

    public $userService;
    public function __construct(UserService $userService){
        
        $this->userService = $userService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => $this->userService->getUsers()]);
    }

    /**
     * CREATE ADMIN USER -> JUST ROOT CAN CREATE THAT
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAdminRequest $request)
    {
        try {
            $newUser = $this->userService->createInstitutionUser($request->all());
            Log::debug(__METHOD__ . ' - NEW USER CREATED ' . json_encode($newUser));
            return response()->json($newUser);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating user"], 400);
        }
    }

    public function getProfile()
    {

    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $data = $request->all();
        $params = Helpers::paramBuilder('User', $data);
        $response = $this->userService->updateUser($request->user()->id, $params);
        return response()->json($response);
    }

    public function resetPassword(RessetPasswordRequest $request)
    {   
        $response = $this->userService->resetPassword($request->user()->id, $request->input('new-password'));
        return response()->json($response);
    }

}
