<?php

namespace App\Classes;

use App\Models\CourseClass;

class CourseClassService
{

    public static function getCourseClasses()
    {
        return CourseClass::with(['course.subject', 'course.teacher', 'course.classroom'])->get();
    }

    public static function getCourseClass($id)
    {
        return CourseClass::with(['course.subject', 'course.teacher', 'course.classroom'])->find($id);
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
