<?php

namespace App\Classes;

use App\Models\{Assignment, StudentAssignment, CourseClass};
use DB;
use Log;
use Illuminate\Support\Carbon;

class AssignmentService
{

    public static function getAssignments()
    {
        return Assignment::with([
            'class',
            'assignmentType',
            'studentsassignment.classroomstudents.student',
            'studentsassignment.classroomstudents.classroom',
            'studentsassignment.assignmentstatus',
        ])->get();
    }

    public static function getAssignment($id)
    {
        return Assignment::where('id', $id)->with([
            'class',
            'assignmentType',
            'studentsassignment.classroomstudents.student',
            'studentsassignment.classroomstudents.classroom',
            'studentsassignment.assignmentstatus',
        ])->first();
    }

    public static function createAssignment($data)
    {

        DB::beginTransaction();
        try {
            $newAssignment = new Assignment();
            $newAssignment->title              = $data['title'];
            $newAssignment->description        = $data['description'];
            $newAssignment->limit_date        =  Carbon::parse($data['limit_date'])->format('Y-m-d H:i:s');
            $newAssignment->class_id           = $data['class_id'];

            $newAssignment->assignment_type_id = $data['assignment_type_id'];


            if ($newAssignment->save()) {
                $newData = [];
                foreach ($data['student_assignments'] as $key) {
                    $newData = [
                        'classroom_student_id' => $key,
                        'score'                =>  $data['score'] ?? 0,
                        'limit_date'           =>  Carbon::parse($data['limit_date'])->format('Y-m-d H:i:s'),
                        'assignment_status_id' => $data['assignment_status_id']
                    ];
                    $newAssignment->studentAssignments()->attach($newAssignment->id, $newData);
                }
            }
            Log::debug(__METHOD__ . ' -> NEW ASSIGNMENT ' . json_encode($newAssignment));
            DB::commit();

            // $course =  CourseClass::where('id', $newAssignment->class_id)->first();
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
            $updateAssignment->limit_date        = $data['limit_date'];
            $updateAssignment->class_id           = $data['class_id'];
            // $updateAssignment->groupqty           = $data['groupqty'] ?? 0;

            $updateAssignment->assignment_type_id = $data['assignment_type_id'];


            if ($updateAssignment->save()) {
                $updateData = [];
                foreach ($data['student_assignments'] as $key) {
                    $update[$key] = [
                        'assignment_id'        => $id,
                        'classroom_student_id' => $key,
                        'score'                => $data['score'] ?? 0,
                        'limit_date'           => $data['limit_date'],
                        'assignment_status_id' => $data['assignment_status_id']
                    ];
                }
                $updateAssignment->studentAssignments()->sync($updateData);
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

        DB::beginTransaction();
        try {
            Assignment::where('id', $id)->delete();
            StudentAssignment::where('assignment_id', $id)->delete();
            Log::debug(__METHOD__ . ' -> DELETE  Assignment and Students Assignment  ' . json_encode($id));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Assignment and Students Assignment  -> ' . json_encode($id) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function getAssignmentByCourse($id)
    {
        return CourseClass::where('course_id', $id)->with([
            'assignments.assignmenttype',
        ])->get();
    }
}
