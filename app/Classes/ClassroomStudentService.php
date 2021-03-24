<?php

namespace App\Classes;

use App\Models\ClassroomStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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



    public static function getRatingStudent($course_type_id)
    {
        $result = [];
        $ratingParse = [];
        $ratings = ClassroomStudent::whereHas('assignmentStudent.assignments.class.course', function (Builder $query) use ($course_type_id) {
            $query->where('course_type_id', '=', $course_type_id);
        })
        ->where('student_id', Auth::user()->student->id)->with(['assignmentStudent.assignments.class.course.subject', 'assignmentStudent.assignments.assignmenttype'])
        ->first();

        if ($ratings) {
            foreach ($ratings['assignmentStudent'] as $key => $value) {
                if ($value->assignment_status_id === 3) {

                    $ratingParse[] = [
                        // 'title' => [
                        'name' => $value->assignments->class->course->subject->name,
                        'name_task' => $value->assignments->title,
                        'score_assignment' => $value->assignments->score,
                        'score_student' => $value->score,
                        'assignment_type' => $value->assignments->assignmenttype->name,
                        'date' => Carbon::parse($value->assignments->created_at)->format('d/m/Y')
                        // ]

                    ];
                }
            }

            $result = collect($ratingParse)->groupBy('name');
        }
        return $result;
    }
}
