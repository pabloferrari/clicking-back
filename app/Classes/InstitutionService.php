<?php

namespace App\Classes;

use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Course;

use App\Classes\Helpers;

class InstitutionService
{

    public static function getInstitutions()
    {
        $institutions = Institution::with(['plan', 'city.province.country'])->get();
        $institutions->each(function($inst) {
            $inst->teachers = Teacher::whereHas('user', function ($query) use ($inst) {
                return $query->where('institution_id', $inst->id);
            })->count();

            $inst->students = Student::whereHas('user', function ($query) use ($inst) {
                return $query->where('institution_id', $inst->id);
            })->count();

            $inst->courses = Course::with(['subject'])->whereHas('subject', function ($query) use ($inst) {
                return $query->where('institution_id', $inst->id);
            })->count();
        });
        return $institutions;
    }

    public static function getInstitution($id)
    {
        return Institution::where('id', $id)->with(['plan', 'city.province.country', 'users', 'users.teacher', 'users.student'])->first();
    }

    public static function createInstitution($data)
    {
        $params = Helpers::paramBuilder('Institution', $data);
        $newInstitution = Institution::create($params);
        return self::getInstitution($newInstitution->id);
    }

    public static function getInstitutionCount($id)
    {
        $students = Student::with('user')->whereHas('user', function ($query) use ($id) {
            return $query->where('institution_id', $id);
        })->count();

        $teachers = Teacher::with('user')->whereHas('user', function ($query) use ($id) {
            return $query->where('institution_id', $id);
        })->count();

        $classrooms = Classroom::where('institution_id', $id)->count();
        return [
            'students' => $students,
            'teachers' => $teachers,
            'classrooms' => $classrooms
        ];
    }

    public static function updateInstitution($id, $data)
    {
        $params = Helpers::paramBuilder('Institution', $data);
        Institution::where('id', $id)->update($params);
        return Institution::where('id', $id)->with(['plan', 'city.province.country'])->first();
    }

    public static function deleteInstitution($id)
    {
        return Institution::where('id', $id)->delete();
    }
}
