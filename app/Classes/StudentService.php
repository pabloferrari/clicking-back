<?php

namespace App\Classes;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use App\Classes\UserService;
use Log;
use DB;

class StudentService
{

    public static function getStudents()
    {
        return Student::whereHas('user', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }

    public static function getStudent($id)
    {
        return Student::with(['user'])->find($id);
    }

    public static function createStudent($data)
    {
        DB::beginTransaction();
        try {
            $data['institution_id'] = $data['institution_id'] ?? Auth::user()->institution_id;
            $userService = new UserService();
            $newUser = $userService->createStudentUser($data);
            Log::debug(__METHOD__ . ' -> NEW USER STUDENT ' . json_encode($newUser));
            $newStudent = new Student();
            $newStudent->name    = $data['name'];
            $newStudent->email   = $data['email'];
            $newStudent->user_id = $newUser->id;

            $newStudent->active  = $data['active'] ?? true;
            $newStudent->save();
            Log::debug(__METHOD__ . ' -> NEW Student ' . json_encode($newStudent));
            DB::commit();
            return self::getStudent($newStudent->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK user student -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateStudent($id, $data)
    {
        $updateStudent = Student::find($id);
        $updateStudent->name    = $data['name'];
        // $updateStudent->phone   = $data['phone'];
        // $updateStudent->user_id = $data['user_id'];
        $updateStudent->active  = $data['active'];
        $updateStudent->save();

        return self::getStudent($id);
    }

    public static function deleteStudent($id)
    {
        DB::beginTransaction();
        try {
            $deleteStudent = Student::find($id);
            $userService = new UserService();

            $userService->deleteUser($deleteStudent->user_id);

            Student::where('id', $id)->delete();
            Log::debug(__METHOD__ . ' -> DELETE  Student and User  ' . json_encode($id));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK user student -> ' . json_encode($id) . ' exception: ' . $e->getMessage());
            return false;
        }
    }
}
