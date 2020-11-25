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

        $CourseSubjectInstution = function ($query) use ($id) {
            $query->where('institution_id', '=', Auth::user()->institution_id);
        };
        return CourseClass::where('course_id', $id)->with(['course.subject', 'course.teacher', 'course.classroom', 'assignments.assignmenttype'])
            ->whereHas('course.subject', $CourseSubjectInstution)->get();
    }

    public static function getCourseClassCount($id)
    {
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
}
