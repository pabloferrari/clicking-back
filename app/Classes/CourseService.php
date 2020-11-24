<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;

class CourseService
{

    public static function getCourses()
    {
        return Course::with(['subject', 'courseType', 'teacher', 'classroom.classroomStudents.student.user', 'classroom.shift'])->whereHas('subject', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }

    public static function getCourse($id)
    {
        $subjectInstution = function ($query) {
            $query->where('institution_id', '=', Auth::user()->institution_id);
        };

        $classroom = function ($query) use ($id) {
            $query->where('id', '=', $id);
        };
        return  Course::with(['subject', 'courseType', 'teacher', 'classroom.classroomStudents.student.user', 'classroom.shift'])
            ->whereHas('classroom', $classroom)
            ->whereHas('subject', $subjectInstution)
            ->get();

        // return  Course::with(['subject', 'courseType', 'teacher', 'classroom.classroomStudents.student.user', 'classroom.shift'])->whereHas('subject', function ($query) {
        //     return $query->where('institution_id', Auth::user()->institution_id);
        // })->whereHas('classroom', function ($query) {
        //     return $query->where('id', );
        // })->get();
    }


    // public static function getCourseCount($id)
    // {
    //     $courses = Course::with('classrooms')->where('classroom_id', $id)->count();

    //     $tasks    = Assignment::with('class.course.classroom')->whereHas('class.course.classroom', function ($query) use ($id) {
    //         return $query->where('classroom_id', $id);
    //     })->whereHas('assignmenttype', function ($query) {
    //         return $query->where('assignment_type_id', 1);
    //     })->count();

    //     $worksPracticals   = Assignment::with('class.course.classroom')->whereHas('class.course.classroom', function ($query) use ($id) {
    //         return $query->where('classroom_id', $id);
    //     })->whereHas('assignmenttype', function ($query) {
    //         return $query->where('assignment_type_id', 2);
    //     })->count();

    //     $exams   = Assignment::with('class.course.classroom')->whereHas('class.course.classroom', function ($query) use ($id) {
    //         return $query->where('classroom_id', $id);
    //     })->whereHas('assignmenttype', function ($query) {
    //         return $query->where('assignment_type_id', 3);
    //     })->count();

    //     return [
    //         'courses'         => $courses,
    //         'tasks'           => $tasks,
    //         'workspracticals' => $worksPracticals,
    //         'exams'           => $exams
    //     ];
    // }

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

    public static function coursesByClassroom($id) {
        return Course::where('classroom_id', $id)->with(['subject', 'courseType', 'teacher', 'classroom.classroomStudents.student.user', 'classroom.shift'])->whereHas('subject', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }
}
