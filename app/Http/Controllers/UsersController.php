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

    public function destroy($id)
    {
        try {
            Log::debug(__METHOD__ . ' - DELETE USER ' . $id);
            return response()->json($this->userService->deleteUser($id));
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - DESTROY USER' . $th->getMessage() . ' - id: ' . $id);
            return response()->json(["message" => "Error deleting user"], 400);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $data = $request->all();
            $params = Helpers::paramBuilder('User', $data);
            $response = $this->userService->updateUser($id, $params);
            return response()->json($response);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - UPDATE USER' . $th->getMessage() . ' - id: ' . $id);
            return response()->json(["message" => "Error updating user"], 400);
        }
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $params = Helpers::paramBuilder('User', $data);
        $response = $this->userService->updateUser($request->user()->id, $params);

        if(isset($data['linkedin'])) {
            $this->userService->setSocialNetwork('linkedin', $data['linkedin']);
        }

        if(isset($data['cv'])) {
            $this->userService->setSocialNetwork('cv', $data['cv']);
        }

        return response()->json($response);
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = $this->userService->updateAvatar($request->all(), $request);
            Log::debug(__METHOD__ . ' - UPDATE USER AVATAR ' . json_encode($user));
            return response()->json(['data' => $user]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating avatar", "error" => $th->getMessage()], 400);
        }
    }

    public function resetPassword(RessetPasswordRequest $request)
    {
        $response = $this->userService->resetPassword($request->user()->id, $request->input('new-password'));
        return response()->json($response);
    }

    public function getUsersInstitution($filter) {
        return response()->json($this->userService->getUsersInstitution($filter));
    }

    public function getProfile() {
        return response()->json(['data' => $this->userService->getProfile()]);
    }

}
