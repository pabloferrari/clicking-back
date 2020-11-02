<?php

namespace App\Classes;

use App\Models\Course;

class CourseService
{

    public static function getCourses()
    {
        return Course::with(['subject', 'courseType', 'teacher', 'classroom'])->get();
    }

    public static function getCourse($id)
    {
        return Course::where('id', $id)->with(['subject', 'courseType', 'teacher', 'classroom'])->first();
    }

    public static function createCourse($data)
    {
        $new = new Course();
        $new->subject_id     = $data['subject_id'];
        $new->course_type_id = $data['course_type_id'];
        $new->teacher_id     = $data['teacher_id'];
        $new->classroom_id   = $data['classroom_id'];
        $new->save();
        return self::getCourse($new->id);
    }

    public static function updateCourse($id, $data)
    {
        $Course = Course::find($id);
        $Course->subject_id     = $data['subject_id'];
        $Course->course_type_id = $data['course_type_id'];
        $Course->teacher_id     = $data['teacher_id'];
        $Course->classroom_id   = $data['classroom_id'];
        $Course->save();
        return self::getCourse($Course->id);
    }

    public static function deleteCourse($id)
    {
        return Course::where('id', $id)->delete();
    }
}
