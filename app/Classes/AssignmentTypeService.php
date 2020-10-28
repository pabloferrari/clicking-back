<?php

namespace App\Classes;

use App\Models\AssignmentType;

class AssignmentTypeService
{

    public static function getAssignmentTypes()
    {
        return AssignmentType::get();
    }

    public static function getAssignmentType($id)
    {
        return  AssignmentType::find($id);
    }

    public static function createAssignmentType($data)
    {
        $newAssignmentType = new AssignmentType();
        $newAssignmentType->name = $data['name'];
        $newAssignmentType->group_enabled = $data['group_enabled'];
        $newAssignmentType->save();
        return self::getAssignmentType($newAssignmentType->id);
    }

    public static function updateAssignmentType($id, $data)
    {
        AssignmentType::where('id', $id)->update($data);
        return self::getAssignmentType($id);
    }

    public static function deleteAssignmentType($id)
    {
        return AssignmentType::where('id', $id)->delete();
    }
}
