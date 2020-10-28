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
        return Student::get();
    }

    public static function getStudent($id)
    {
        return Student::find($id);
    }

    public static function createStudent($data)
    {
        DB::beginTransaction();
        try {
            // $data['institution_id'] = $data['institution_id'] ?? Auth::user()->institution_id;
            //$userService = new UserService();
            //$newUser = $userService->createStudentUser($data);
            //Log::debug(__METHOD__ . ' -> NEW USER ' . json_encode($newUser));
            $newStudent = new Student();
            $newStudent->name    = $data['name'];
            // $newStudent->phone   = $data['phone'];
            // $newStudent->user_id = $newUser->id;
            $newStudent->user_id = $data['user_id'];
            $newStudent->active  = $data['active'] ?? true;
            $newStudent->save();
            Log::debug(__METHOD__ . ' -> NEW Student ' . json_encode($newStudent));
            DB::commit();
            return $newStudent;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK user -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateStudent($id, $data)
    {
        $updateStudent = Student::find($id);
        $updateStudent->name    = $data['name'];
        // $updateStudent->phone   = $data['phone'];
        $updateStudent->user_id = $data['user_id'];
        $updateStudent->active  = $data['active'];
        $updateStudent->save();
        return self::getStudent($id);
    }

    public static function deleteStudent($id)
    {
        return Student::where('id', $id)->delete();
    }
}
