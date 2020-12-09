<?php

namespace App\Classes;

use App\Models\AssignmentGroup;
use Log;

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
        if (isset($data['student_id'])) {
            $i = 1;
            foreach ($data['student_id'] as $key) {
                if ($key) {
                    foreach ($key as $value) {
                        $newAssignmentGroup = new AssignmentGroup();
                        $newAssignmentGroup->assignment_id   = $data['assignmentId'];
                        $newAssignmentGroup->classroom_student_id = $value['id'];
                        $newAssignmentGroup->num                  = $i;
                        $newAssignmentGroup->save();
                        Log::debug(__METHOD__ . ' -> NEW ASSIGNMENTGROUP ' . json_encode($newAssignmentGroup));
                    }
                    $i++;
                }
            }
        }

        //$newAssignmentGroup->save();
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
