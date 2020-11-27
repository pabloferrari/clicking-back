<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\CourseClass;
use App\Models\Assignment;

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
        return self::getCourseClass($newCourseClass->id);
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

    public static function getCourseClassInstitutionCount($id)
    {
        $Institution = function ($query) {
            $query->where('institution_id', '=', Auth::user()->institution_id);
        };

        $assistance = CourseClass::where('course_id', $id)->with('assignments.studentsassignment.classroomstudents')
            ->whereHas('course.subject', $Institution)->count();


        // assignment type task 1
        $tasks =  CourseClass::where('course_id', $id)->with('assignments')
            ->whereHas('course.subject', $Institution)
            ->withCount(['assignments' => function ($query) {
                $query->where('assignment_type_id', 1);
            }])->count();

        // assignment type evaluations 3 
        $evaluations = CourseClass::where('course_id', $id)->with('assignments')
            ->whereHas('course.subject', $Institution)
            ->withCount(['assignments' => function ($query) {
                $query->where('assignment_type_id', 3);
            }])->count();
        return [
            'assistance' => $assistance,
            'tasks' => $tasks,
            'evaluations' => $evaluations
        ];
    }
}
