<?php

namespace App\Classes;

use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Classes\UserService;
use Log;
use DB;
use Hash;

class TeacherService
{

    public static function getTeachers()
    {
        return Teacher::whereHas('user', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }
    public static function getTeachersByInstitution($id)
    {
        return Teacher::whereHas('user', function ($query) use ($id) {
            return $query->where('institution_id', $id);
        })->get();
    }

    public static function getTeacher($id)
    {
        return Teacher::with(['user'])->find($id);
    }

    public static function createTeacher($data)
    {
        DB::beginTransaction();
        try {
            $data['institution_id'] = $data['institution_id'] ?? Auth::user()->institution_id;
            $userService = new UserService();
            $newUser = $userService->createTeacherUser($data);
            Log::debug(__METHOD__ . ' -> NEW USER ' . json_encode($newUser));
            $newTeacher = new Teacher();
            $newTeacher->name    = $data['name'];
            $newTeacher->email   = $data['email'];
            $newTeacher->phone   = $data['phone'];
            $newTeacher->user_id = $newUser->id;
            $newTeacher->active  = $data['active'] ?? true;
            $newTeacher->save();
            Log::debug(__METHOD__ . ' -> NEW TEACHER ' . json_encode($newTeacher));
            DB::commit();
            return $newTeacher;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK user -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateTeacher($id, $data)
    {
        $updateTeacher = Teacher::find($id);
        $updateTeacher->name    = $data['name'];
        $updateTeacher->email   = $data['email'];
        $updateTeacher->phone   = $data['phone'];
        $updateTeacher->active  = $data['active'];
        $updateTeacher->save();
        
        $dataUpdateUser = ['email' => $data['email'], 'active' => $data['active']];
        if(isset($data['password']) && $data['password'] != '') 
        $dataUpdateUser['password'] = Hash::make($data['password']);
        $userService = new UserService();
        $userService->updateUser($updateTeacher->user->id, $dataUpdateUser);
        
        return self::getTeacher($id);
    }

    public static function deleteTeacher($id)
    {
        return Teacher::where('id', $id)->delete();
    }
}
