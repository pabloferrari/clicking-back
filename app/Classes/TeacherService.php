<?php

namespace App\Classes;
use App\Models\Teacher;

class TeacherService {

    public static function getTeachers()
    {
        return Teacher::with(['turns','commissions'])->get();
    }

    public static function getTeacher($id)
    {
        return Teacher::with(['turns','commissions'])->find($id);
    }

    public static function createTeacher($data)
    {
        $newTeacher = new Teacher();
        $newTeacher->name    = $data['name'];
        $newTeacher->email   = $data['email'];
        $newTeacher->phone   = $data['phone'];
        $newTeacher->user_id = $data['user_id'];
        $newTeacher->active  = $data['active'];
        $newTeacher->save();
        $newTeacher->turns()->attach($data['turns']);
        $newTeacher->commissions()->attach($data['commissions']);
        return self::getTeacher($newTeacher->id);
    }

    public static function updateTeacher($id,$data)
    {
        $updateTeacher = Teacher::find($id);
        $updateTeacher->name    = $data['name'];
        $updateTeacher->email   = $data['email'];
        $updateTeacher->phone   = $data['phone'];
        $updateTeacher->user_id = $data['user_id'];
        $updateTeacher->active  = $data['active'];
        $updateTeacher->save();

        $updateTeacher->turns()->sync($data['turns']);
        $updateTeacher->commissions()->sync($data['commissions']);
        return self::getTeacher($id);
    }

    public static function deleteTeacher($id)
    {
        return Teacher::where('id', $id)->delete();
    }
}
