<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\CourseClass;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\IntitutionClass;
use App\Models\Classroom;
use App\Models\{Meeting,MeetingUser};
use DB;

class CourseClassService
{

    public static function getCourseClasses()
    {
        return CourseClass::with(['course.subject', 'course.teacher', 'course.classroom', 'assignments.studentAssignments'])
            ->whereHas('course.subject', function ($query) {
                return $query->where('institution_id', Auth::user()->institution_id);
            })->get();
    }

    public static function getCourseClass($id)
    {

        $CourseSubjectInstution = function ($query) {
            $query->where('institution_id', '=', Auth::user()->institution_id);
        };
        return CourseClass::where('course_id', $id)->with(['course.subject', 'course.teacher', 'course.classroom', 'assignments.assignmenttype'])
            ->whereHas('course.subject', $CourseSubjectInstution)->get();
    }

    public static function createCourseClass($data)
    {
        $newCourseClass = new CourseClass();
        $newCourseClass->title = $data['title'];
        $newCourseClass->description = $data['description'];
        $newCourseClass->course_id = $data['course_id'];
        $newCourseClass->save();
        return self::getCourseClass($newCourseClass->course_id);
    }

    public static function updateCourseClass($id, $data)
    {
        CourseClass::where('id', $id)->update($data);
        return self::getCourseClass($id);
    }

    public static function deleteCourseClass($id)
    {
        return CourseClass::where('id', $id)->delete();
    }

    public static function getCourseClassByStudents($id)
    {
        return CourseClass::where('course_id', $id)->with('course.classroom.classroomstudents')->get();
    }


    public static function getCourseClassInstitutionCount($id)
    {
        $Institution = function ($query) {
            return $query->where('institution_id', '=', Auth::user()->institution_id);
        };

        $courseStudentTotal = Course::where('id', $id)->with('classroom.classroomstudents')->get();
        $countStudent = 0;
        foreach ($courseStudentTotal as $key => $value) {
            $countStudent = count($value->classroom->classroomstudents);
        }

        
        // $assistance = DB::table('student_assignments')
        //     ->select(DB::raw('count(*) as count_students'))
        //     ->leftJoin('assignments', 'assignments.id', '=', 'student_assignments.assignment_id')
        //     ->leftJoin('classes', 'classes.id', '=', 'assignments.class_id')
        //     ->where('classes.course_id', $id)
        //     ->get();
        // dd($assistance);
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            
            $classes = CourseClass::where('course_id', $id)->get()->pluck('id')->toArray();
            $meetings = Meeting::where('model', 'class')->whereIn('model_id', $classes)->get()->pluck('id')->toArray();
            $meetingUsers = MeetingUser::whereIn('meeting_id', $meetings)->get();
            $countStudents = count($meetingUsers);
            $assistance = 0;
            foreach ($meetingUsers as $mu) {
                $assistance += $mu->joined;
            }
            $totalAssitance = $countStudents > 0 ? ($assistance / $countStudents)*100 : 0;

        } else if ($user->hasRole('student')) {

            $classes = CourseClass::where('course_id', $id)->get()->pluck('id')->toArray();
            $meetings = Meeting::where('model', 'class')->whereIn('model_id', $classes)->get()->pluck('id')->toArray();
            $meetingUsers = MeetingUser::whereIn('meeting_id', $meetings)->where('user_id', $user->id)->get();
            $countStudents = count($meetingUsers);
            $assistance = 0;
            foreach ($meetingUsers as $mu) {
                $assistance += $mu->joined;
            }
            $totalAssitance = $countStudents > 0 ? ($assistance / $countStudents)*100 : 0;

        }

        // assignment type task 1
        $tasks =  CourseClass::where('course_id', $id)->with('assignments')
            ->whereHas('course.subject', $Institution)
            ->whereHas('assignments', function ($query) {
                return $query->where('assignment_type_id', '=', 1);
            })
            ->count();

        // assignment type evaluations 2
        $evaluations = CourseClass::where('course_id', $id)->with('assignments')
            ->whereHas('course.subject', $Institution)
            ->whereHas('assignments', function ($query) {
                return $query->where('assignment_type_id', '=', 2);
            })
            ->count();

        return [
            'assistance' => round($totalAssitance, 2),
            'tasks' => $tasks,
            'evaluations' => $evaluations
        ];
    }

    public function getUsersByClass($classId) {
        $users = [];
        $couseClass = IntitutionClass::where('id', $classId)->first();
        $classroom = Classroom::where('id', $couseClass->course->classroom_id)->first();
        foreach($classroom->classroomStudents as $st){
            $users[] = $st->student->user_id;
        }
        return $users;
    }
}
