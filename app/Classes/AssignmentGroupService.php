<?php

namespace App\Classes;

use App\Models\AssignmentGroup;

class AssignmentGroupService
{

    public static function getAssignmentGroups()
    {
        return AssignmentGroup::with(['assignment.class', 'assignment.assignmenttype', 'classroomstudents.classroom', 'classroomstudents.student'])->get();
    }

    public static function getAssignmentGroup($id)
    {
        return AssignmentGroup::where('id', $id)->with(['assignment.class', 'assignment.assignmenttype', 'classroomstudents.classroom', 'classroomstudents.student'])->first();
    }

    public static function createAssignmentGroup($data)
    {
        $newAssignmentGroup = new AssignmentGroup();
        $newAssignmentGroup->assignment_id   = $data['assignment_id'];
        $newAssignmentGroup->classroom_student_id = $data['classroom_student_id'];
        $newAssignmentGroup->num             = $data['num'];

        $newAssignmentGroup->save();
        return self::getAssignmentGroup($newAssignmentGroup->id);
    }

    public static function updateAssignmentGroup($id, $data)
    {
        AssignmentGroup::where('id', $id)->update($data);
        return self::getAssignmentGroup($id);
    }

    public static function deleteAssignmentGroup($id)
    {
        return AssignmentGroup::where('id', $id)->delete();
    }
}
