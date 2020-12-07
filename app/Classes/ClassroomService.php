<?php

namespace App\Classes;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Classes\CourseService;
use Illuminate\Support\Facades\Auth;
use App\Classes\createClassroomStudent;
use Log;
use DB;

class ClassroomService
{

    public static function getClassrooms()
    {
        return Classroom::with(['shift', 'institution', 'courses.subject', 'courses.teacher', 'classroomStudents.student.user'])
            ->where('institution_id', Auth::user()->institution_id)
            ->get();
    }

    public static function getClassroom($id)
    {
        return Classroom::where('id', $id)
            ->where('institution_id', Auth::user()->institution_id)
            ->with(['shift', 'institution', 'courses.subject', 'courses.teacher', 'classroomStudents'])->first();
    }

    public static function getClassroomInstitution($id)
    {
        return Classroom::with(['shift', 'institution', 'classroomStudents.student.user'])->withCount('courses')->where('institution_id', $id)->get();
    }

    public static function getClassroomCount($id)
    {
        $courses = Course::with('classrooms')->where('classroom_id', $id)->count();

        $students = Student::with('classroomStudents.classroom')->whereHas('classroomStudents.classroom', function ($query) use ($id) {
            return $query->where('classroom_id', $id);
        })->count();

        $teachers   = Teacher::with('courses.classroom')->whereHas('courses.classroom', function ($query) use ($id) {
            return $query->where('classroom_id', $id);
        })->count();

        return [
            'courses'  => $courses,
            'students' => $students,
            'teachers' => $teachers
        ];
    }


    public static function createClassroom($data)
    {
        DB::beginTransaction();
        try {
            // Insert Classroom
            $new = new Classroom();
            $new->name     = $data['name'];
            $new->shift_id = $data['shift_id'];
            $new->institution_id = $data['institution_id'];
            $new->save();

            // Insert Course
            if (isset($data['courses'])) {
                foreach ($data['courses'] as $key) {
                    $key['classroom_id'] = $new->id;
                    $courseService = new CourseService();
                    $courseService->createCourse($key);
                    Log::debug(__METHOD__ . ' -> NEW COURSE ' . json_encode($key));
                }
            }

            // Insert Student
            //$new->classroomStudentsPivot()->attach($data['student_id']);
            $ArrayStudents = [];
            if (isset($data['student_id'])) {
                foreach ($data['student_id'] as $key => $value) {
                    $classroomStudentService = new ClassroomStudentService();
                    $ArrayStudents['student_id']   = $value['id'];
                    $ArrayStudents['classroom_id'] = $new->id;
                    $classroomStudentService->createClassroomStudent($ArrayStudents);
                    Log::debug(__METHOD__ . ' -> NEW CLASSROOM STUDENT ' . json_encode($key));
                }
            }

            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW CLASSROOM ' . json_encode($new));
            return self::getClassroom($new->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK CLASSROOM -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function updateClassroom($id, $data)
    {
        DB::beginTransaction();
        try {
            $Classroom = new Classroom();
            $Classroom->name     = $data['name'];
            $Classroom->shift_id = $data['shift_id'];
            $Classroom->institution_id = $data['institution_id'];
            $Classroom->save();

            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW CLASSROOM ' . json_encode($Classroom));
            return self::getClassroom($Classroom->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK CLASSROOM -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteClassroom($id)
    {
        return Classroom::where('id', $id)->delete();
    }
}
