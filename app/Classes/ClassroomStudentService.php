<?php

namespace App\Classes;

use App\Models\ClassroomStudent;

class ClassroomStudentService
{

    public static function getClassroomStudents()
    {
        return ClassroomStudent::with(['classroom.shift', 'classroomStudentsPivot'])->get()->unique('classroom.id');
    }

    public static function getClassroomStudent($id)
    {
        return ClassroomStudent::where('id', $id)->with(['classroom', 'student'])->first();
    }

    public static function createClassroomStudent($data)
    {
        $new = new ClassroomStudent();
        $new->student_id   = $data['student_id'];
        $new->classroom_id = $data['classroom_id'];
        $new->save();
        return self::getClassroomStudent($new->id);
    }

    public static function updateClassroomStudent($id, $data)
    {
        $ClassroomStudent = ClassroomStudent::find($id);
        $ClassroomStudent->student_id   = $data['student_id'];
        $ClassroomStudent->classroom_id = $data['classroom_id'];
        $ClassroomStudent->save();
        return self::getClassroomStudent($ClassroomStudent->id);
    }

    public static function deleteClassroomStudent($id)
    {
        return ClassroomStudent::where('id', $id)->delete();
    }
}
