<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Subject;

class SubjectService
{

    public static function getSubjects()
    {
        return Subject::with(['institution.plan','institution.city.province.country'])->where('institution_id', Auth::user()->institution_id)->get();
    }

    public static function getSubject($id)
    {
        return Subject::where('id', $id)->with(['institution.plan','institution.city.province.country'])->where('institution_id', Auth::user()->institution_id)->first();
    }

    public static function createSubject($data)
    {
        $new = new Subject();
        $new->name = $data['name'];
        $new->institution_id = isset($data['institution_id']) ? $data['institution_id'] : Auth::user()->institution_id;
        $new->save();
        return self::getSubject($new->id);
    }

    public static function updateSubject($id, $data)
    {
        $Subject = Subject::find($id);
        $Subject->name           = $data['name'];
        $Subject->institution_id = isset($data['institution_id']) ? $data['institution_id'] : Auth::user()->institution_id;
        $Subject->save();
        return self::getSubject($Subject->id);
    }

    public static function deleteSubject($id)
    {
        return Subject::where('id', $id)->delete();
    }
}
