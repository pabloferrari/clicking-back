<?php

namespace App\Classes;

use App\Models\Classroom;

class ClassroomService
{

    public static function getClassrooms()
    {
        return Classroom::with(['shift', 'institution.plan', 'institution.city.province.country'])->get();
    }

    public static function getClassroom($id)
    {
        return Classroom::where('id', $id)->with(['shift', 'institution.plan', 'institution.city.province.country'])->first();
    }

    public static function createClassroom($data)
    {
        $new = new Classroom();
        $new->name     = $data['name'];
        $new->shift_id = $data['shift_id'];
        $new->institution_id = $data['institution_id'];
        $new->save();
        return self::getClassroom($new->id);
    }

    public static function updateClassroom($id, $data)
    {
        $Classroom = Classroom::find($id);
        $Classroom->name     = $data['name'];
        $Classroom->shift_id = $data['shift_id'];
        $Classroom->institution_id = $data['institution_id'];
        $Classroom->save();
        return self::getClassroom($Classroom->id);
    }

    public static function deleteClassroom($id)
    {
        return Classroom::where('id', $id)->delete();
    }
}
