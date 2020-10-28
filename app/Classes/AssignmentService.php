<?php

namespace App\Classes;

use App\Models\Assignment;
use DB;
use Log;

class AssignmentService
{

    public static function getAssignments()
    {
        return Assignment::with('class', 'assignmentType')->get();
    }

    public static function getAssignment($id)
    {
        return Assignment::where('id', $id)->with(['class', 'assignmentType'])->first();
    }

    public static function createAssignment($data)
    {

        DB::beginTransaction();
        try {
            $newAssignment = new Assignment();
            $newAssignment->title              = $data['title'];
            $newAssignment->description        = $data['description'];
            $newAssignment->class_id           = $data['class_id'];
            $newAssignment->assignment_type_id = $data['assignment_type_id'];

            if ($newAssignment->save()) {
                $newAssignment->studentAssignments()->attach($data['classroom_students']);
            }
            Log::debug(__METHOD__ . ' -> NEW ASSIGNMENT ' . json_encode($newAssignment));
            DB::commit();
            return self::getAssignment($newAssignment->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Assignment -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateAssignment($id, $data)
    {
        DB::beginTransaction();
        try {
            $updateAssignment =  Assignment::find($id);
            $updateAssignment->title              = $data['title'];
            $updateAssignment->description        = $data['description'];
            $updateAssignment->class_id           = $data['class_id'];
            $updateAssignment->assignment_type_id = $data['assignment_type_id'];

            if ($updateAssignment->save()) {
                $updateAssignment->studentAssignments()->sync($data['classroom_students']);
            }
            Log::debug(__METHOD__ . ' -> UPDATE ASSIGNMENT ' . json_encode($updateAssignment));
            DB::commit();
            return self::getAssignment($id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Assignment -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        };
    }

    public static function deleteAssignment($id)
    {
        return Assignment::where('id', $id)->delete();
    }
}
