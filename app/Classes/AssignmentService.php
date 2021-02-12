<?php

namespace App\Classes;

use Illuminate\Database\Query\Builder as QueryBuilder;
use App\Models\{Assignment, StudentAssignment, CourseClass};
use DB;
use Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public static function createAssignment($data, $request = null)
    {
        DB::beginTransaction();

        try {
            $newAssignment = new Assignment();
            $newAssignment->title              = $data['title'];
            $newAssignment->description        = $data['description'];
            $newAssignment->limit_date        =  Carbon::parse($data['limit_date'])->format('Y-m-d H:i:s');
            $newAssignment->class_id           = $data['class_id'];
            $newAssignment->groupqty           = $data['groupqty'] ?? 0;
            $newAssignment->assignment_type_id = $data['assignment_type_id'];

            $resultFile = false;
            if ($newAssignment->save()) {
                $newData = [];
                foreach ($data['student_assignments'] as $key) {
                    $newData = [
                        'classroom_student_id' => $key,
                        'score'                =>  0,
                        'limit_date'           => Carbon::parse($data['limit_date'])->format('Y-m-d H:i:s'),
                        'assignment_status_id' => $data['assignment_status_id']
                    ];
                    $newAssignment->studentAssignments()->attach($newAssignment->id, $newData);
                }

                // Load File FileUpload
                $handleFilesUploadService = new handleFilesUploadService();
                $dataFile = array(
                    'model_name' => 'Assignment',
                    'model_id'   => $newAssignment->id,
                    'request'    => $request
                );
                $resultFile = $handleFilesUploadService->createFile($dataFile);
            }

            Log::debug(__METHOD__ . ' -> NEW ASSIGNMENT ' . json_encode($newAssignment));

            if ($resultFile) {
                DB::commit();
                return self::getAssignment($newAssignment->id);
            } else {
                DB::rollback();
                return false;
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Assignment -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function createAssignmentStudent($data)
    {
        DB::beginTransaction();
        try {
            $newAssignment = new Assignment();
            $newData = [
                'assignment_id'        =>  $data['assignment_id'],
                'classroom_student_id' => $data['classroom_student_id'],
                'score'                => $data['score'] ?? 0,
                'limit_date'           => Carbon::parse(Carbon::now()->format('Y-m-d H:i:s')),
                'assignment_status_id' => $data['assignment_status_id']
            ];
            $newAssignment->studentAssignments()->attach($data['assignment_id'], $newData);
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW Assignment Student ' . json_encode($newAssignment));

            return self::getAssignmentDetailById($data['assignment_id']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Assignment Student -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }
    public static function deliverAssignmentStudent($data)
    {
        DB::beginTransaction();
        try {
            $newAssignment = new Assignment();
            $newData = [
                'assignment_id'        =>  $data['assignment_id'],
                'classroom_student_id' => $data['classroom_student_id'],
                'score'                => $data['score'] ?? 0,
                'limit_date'           => Carbon::parse(Carbon::now()->format('Y-m-d H:i:s')),
                'assignment_status_id' => $data['assignment_status_id']
            ];
            $newAssignment->studentAssignments()->attach($data['assignment_id'], $newData);
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW Deliver Assignment Student ' . json_encode($newAssignment));

            return self::getAssignmentDetailById($data['assignment_id']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Deliver Assignment Student -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
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
            $updateAssignment->limit_date         = Carbon::parse($data['limit_date'])->format('Y-m-d H:i:s');
            $updateAssignment->class_id           = $data['class_id'];
            $updateAssignment->groupqty           = $data['groupqty'] ?? 0;

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

    public static function getAssignmentByTeacher($id, $status)
    {
        return Assignment::where('assignment_type_id', $id)->with([
            'assignmenttype', 'class.course.classroom.shift', 'studentsassignment.classroomstudents.student.user', 'studentsassignment.assignmentstatus'
        ])
            ->whereHas('studentsassignment', function ($query) use ($status) {
                return $query->where('assignment_status_id', $status);
            })
            ->whereHas('class.course.classroom', function ($query) {
                return $query->where('institution_id', Auth::user()->institution_id);
            })
            ->whereHas('class.course', function ($query) {
                return $query->where('teacher_id', Auth::user()->teacher->id);
            })->get();
    }

    public static function getAssignmentByStudent($id, $status)
    {

        return Assignment::where('assignment_type_id', $id)->with([
            'assignmenttype', 'class.course.subject'
            // ,'studentsassignment.classroomstudents.student'
            , 'studentsassignment.assignmentstatus'
        ])
            ->whereHas('studentsassignment', function ($query) use ($status) {
                return $query->where('assignment_status_id', $status);
            })
            ->whereHas('class.course.classroom', function ($query) {
                return $query->where('institution_id', Auth::user()->institution_id);
            })
            ->whereHas('class.course.classroom.classroomStudents', function ($query) {
                return $query->where('student_id', Auth::user()->student->id);
            })->get();
    }

    public static function getAssignmentDetailById($id)
    {

        $assignment = Assignment::where('id', $id)
            ->with(['class.course.teacher.user', 'assignmenttype'])
            ->first();

        $studentAssignmentIN = DB::select("SELECT id
            FROM student_assignments
            WHERE id IN(
                SELECT MAX(id) AS id
                FROM student_assignments
                WHERE assignment_id = ?
                GROUP BY classroom_student_id
            )", [$id]);
        $studentAssignmentIN =  collect($studentAssignmentIN)->map(function ($x) {
            return (array) $x;
        })->toArray();

        $studentAssignment = StudentAssignment::whereIn('id',  $studentAssignmentIN)->with(['classroomstudents.student.user', 'assignmentstatus'])
            ->get();

        return [
            'studentsassignment' => $studentAssignment,
            'assignment'         => $assignment
        ];
    }
}
