<?php

namespace App\Classes;

use App\Models\CourseType;

class CourseTypeService
{

    public static function getCourseTypes()
    {
        return CourseType::with(['institution.plan', 'institution.city.province.country'])->get();
    }

    public static function getCourseType($id)
    {
        return CourseType::where('id', $id)->with(['institution.plan', 'institution.city.province.country'])->first();
    }

    public static function createCourseType($data)
    {
        $new = new CourseType();
        $new->name = $data['name'];
        $new->institution_id = $data['institution_id'];
        $new->save();
        return self::getCourseType($new->id);
    }

    public static function updateCourseType($id, $data)
    {
        $CourseType = CourseType::find($id);
        $CourseType->name       = $data['name'];
        $CourseType->institution_id = $data['institution_id'];
        $CourseType->save();
        return self::getCourseType($CourseType->id);
    }

    public static function deleteCourseType($id)
    {
        return CourseType::where('id', $id)->delete();
    }
}
