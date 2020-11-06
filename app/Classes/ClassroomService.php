<?php

namespace App\Classes;

use App\Models\Classroom;
use App\Classes\CourseService;
use Log;
use DB;

class ClassroomService
{

    public static function getClassrooms()
    {
        return Classroom::with(['shift', 'institution.plan', 'institution.city.province.country'])->get();
    }

    public static function getClassroom($id)
    {
        return Classroom::where('id', $id)->with(['shift', 'institution.plan', 'institution.city.province.country'])->first();
    }

    public static function createClassroom($data)
    {
        DB::beginTransaction();
        try{
            // Insert Classroom
            $new = new Classroom();
            $new->name     = $data['name'];
            $new->shift_id = $data['shift_id'];
            $new->institution_id = $data['institution_id'];
            $new->save();

            // Insert Course
            foreach ($data['courses'] as $key) {
                $key['classroom_id'] = $new->id;
                $courseService = new CourseService();
                $courseService->createCourse($key);
                Log::debug(__METHOD__ . ' -> NEW COURSE ' . json_encode($key));
            }

            // Insert Student
            $new->classroomStudentsPivot()->attach($data['student_id']);
            Log::debug(__METHOD__ . ' -> NEW CLASSROOM STUDENT ' . json_encode($new));

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
        try{
            $Classroom = Classroom::find($id);
            $Classroom->name     = $data['name'];
            $Classroom->shift_id = $data['shift_id'];
            $Classroom->institution_id = $data['institution_id'];

            if ( $Classroom->save() ){

                // $newData = [];
                // foreach ($data['courses'] as $key) {
                //     $newData = [
                //         'classroom_student_id' => $key,
                //     ];
                //     $newAssignment->studentAssignments()->attach($newAssignment->id, $newData);
                // }


                DB::commit();
                Log::debug(__METHOD__ . ' -> NEW CLASSROOM ' . json_encode($Classroom));
                return self::getClassroom($Classroom->id);
            }else{
                DB::rollback();
                Log::error(__METHOD__ . ' - ROLLBACK user -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
                return false;
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK user -> ' . json_encode($data) . ' exception: ' . $e->getMessage());
            return false;
        }
    }

    public static function deleteClassroom($id)
    {
        return Classroom::where('id', $id)->delete();
    }
}
