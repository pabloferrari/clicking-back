<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\{Classroom, Course, CourseType, ClassroomStudent, Student};
use App\Models\Assignment;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class CourseService
{

    public static function getCourses()
    {
        return Course::with(['subject', 'courseType', 'teacher.user', 'classroom.classroomStudents.student.user', 'classroom.shift'])->whereHas('subject', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }

    public static function getCourse($id)
    {
        $InstitutionCourse = function ($query) {
            $query->where('institution_id', '=', Auth::user()->institution_id);
        };

        return Course::where('id', $id)->with('subject', 'teacher.user.socialnetworks', 'coursetype', 'classroom.classroomStudents.student.user.socialnetworks')
            ->wherehas('subject', $InstitutionCourse)
            ->get();
    }

    public static function getCourseClassesCount($id)
    {

        $tasks    = Assignment::with('class.course.classroom')
            ->whereHas('class', function ($query) use ($id) {
                return $query->where('course_id', $id);
            })->whereHas('assignmenttype', function ($query) {
                return $query->where('assignment_type_id', 1);
            })->count();

        $exams   = Assignment::with('class.course.classroom')->whereHas('class', function ($query) use ($id) {
            return $query->where('course_id', $id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 3);
        })->count();

        $assistance = Assignment::with('studentsassignment')->whereHas('class', function ($query) use ($id) {
            return $query->where('course_id', $id);
        })->count();

        return [
            // 'courses'         => $courses,
            'tasks'           => $tasks,
            'assistance' => $assistance,
            'exams'           => $exams
        ];
    }


    public static function getMyCoursesAssignmentsCountTeacher()
    {
        $teacher_id = Auth::user()->teacher->id;

        $courses = Course::where('teacher_id', $teacher_id)->count();

        $tasks    = Assignment::with('class.course.classroom')->whereHas('class.course', function ($query) use ($teacher_id) {
            return $query->where('teacher_id', $teacher_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 1);
        })->count();

        $worksPracticals   = Assignment::with('class.course.classroom')->whereHas('class.course', function ($query) use ($teacher_id) {
            return $query->where('teacher_id', $teacher_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 3);
        })->count();

        $exams   = Assignment::with('class.course.classroom')->whereHas('class.course', function ($query) use ($teacher_id) {
            return $query->where('teacher_id', $teacher_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 2);
        })->count();

        return [
            'courses'         => $courses,
            'tasks'           => $tasks,
            'workpractice'    => $worksPracticals,
            'exams'           => $exams
        ];
    }
    public static function getMyCoursesAssignmentsCountStudent()
    {
        $student_id = Auth::user()->student->id;

        $courses = Course::whereHas('classroom.classroomstudents', function ($query) use ($student_id) {
            return $query->where('student_id', $student_id);
        })->count();

        $tasks    = Assignment::whereHas('class.course.classroom.classroomstudents', function ($query) use ($student_id) {
            return $query->where('student_id', $student_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 1);
        })->count();

        $worksPracticals   = Assignment::whereHas('class.course.classroom.classroomstudents', function ($query) use ($student_id) {
            return $query->where('student_id', $student_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 3);
        })->count();

        $exams   = Assignment::whereHas('class.course.classroom.classroomstudents', function ($query) use ($student_id) {
            return $query->where('student_id', $student_id);
        })->whereHas('assignmenttype', function ($query) {
            return $query->where('assignment_type_id', 2);
        })->count();

        return [
            'courses'         => $courses,
            'tasks'           => $tasks,
            'workpractice'    => $worksPracticals,
            'exams'           => $exams
        ];
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

    public static function coursesByClassroom($id)
    {
        return Course::where('classroom_id', $id)->with(['subject', 'courseType', 'teacher.user', 'classroom.classroomStudents.student.user', 'classroom.shift'])->whereHas('subject', function ($query) {
            return $query->where('institution_id', Auth::user()->institution_id);
        })->get();
    }

    public static function getCoursesTeacher()
    {
        $courses = Course::where('teacher_id', Auth::user()->teacher->id)->with(['subject', 'courseType', 'classroom.classroomStudents.student.user', 'classroom.shift'])->get();
        $coursesByType = [];
        foreach ($courses as $course) {
            $coursesByType[$course->courseType->name][] = $course;
        }
        return $coursesByType;
    }


    public static function getCoursesStudent()
    {
        $classroomIds = [];
        foreach (Auth::user()->student->classroomStudents as $cst) {
            $classroomIds[] = $cst->classroom_id;
        }
        $courses = Course::whereIn('classroom_id', $classroomIds)->with(['subject', 'courseType', 'classroom', 'classroom.shift', 'classroom.classroomStudents.student.user'])->get();
        $coursesByType = [];
        foreach ($courses as $course) {
            $coursesByType[$course->courseType->name][] = $course;
        }
        return $coursesByType;
    }

    public static function getStudentNotInCourse($id)
    {

        $classroom = Classroom::where('institution_id', Auth::user()->institution_id)->with('courses')
            ->whereHas('courses', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();

        $studentInClassRoom = ClassroomStudent::where('classroom_id', $classroom->id)->get();
        $studentIn = [];
        foreach ($studentInClassRoom as $student) {
            $studentIn[] = $student->student_id;
        }
        $students = Student::with('user')
            ->whereHas('user', function ($query) {
                $query->where('institution_id', Auth::user()->institution_id);
            })
            ->whereNotIn('id', $studentIn)->get();

        return $students;
    }

    public static function addStudentInCourse($data)
    {
        $course_id = $data['course_id'];
        $student_id  =  $data['student_id'];
        $classroom = Classroom::where('institution_id', Auth::user()->institution_id)->with('courses')
            ->whereHas('courses', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })
            ->first();


        DB::beginTransaction();
        try {

            foreach ($student_id as $student) {
                $newClassroomStudent = new ClassroomStudent();
                $newClassroomStudent->student_id = $student;
                $newClassroomStudent->classroom_id = $classroom->id;
                $newClassroomStudent->save();
            }
            Log::debug(__METHOD__ . ' -> NEW STUDENT IN COURSE ' . json_encode($newClassroomStudent));
            DB::commit();
            return self::getCourse($course_id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK STUDENT in Copurse -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteStudentCourse($id)
    {
        DB::beginTransaction();
        try {
            ClassroomStudent::where('id', $id)->delete();
            Log::debug(__METHOD__ . ' -> DELETE  Student in Course  ' . json_encode($id));
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK Student in Course  -> ' . json_encode($id) . ' exception: ' . $e->getMessage());
            return false;
        }
    }
}
