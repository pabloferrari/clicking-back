<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest\CreateAdminRequest;
use App\Classes\UserService;
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
}
