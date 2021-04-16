<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;
use Hash;
use DB;

use App\Classes\{Helpers, NotificationService};
use App\Models\{ User, Role, RoleUser, Student, Teacher, ClassroomStudent, StudentAssignment, MeetingUser};

class DashboardService
{

    public $tasks;
    public $tps;
    public $exams;
    public $assistance;
    public $absences;
       
    public function studentDashboard($user) {
        
        $classes = ClassroomStudent::where('student_id', $user->student->id)->get()->pluck('id');
        $studentAssignment = StudentAssignment::with(['assignments', 'assignments.assignmenttype'])->whereIn('classroom_student_id', $classes)->get();
        $assignments = collect($studentAssignment)->groupBy('assignment_id');
        $asistencias = MeetingUser::where('user_id', $user->id)->get();
        $asistencias = collect($asistencias)->groupBy('joined');
        
        $this->tasks = 0;
        $this->tps = 0;
        $this->exams = 0;
        $this->assistance = 0;
        $this->absences = 0;

        try {
            
            collect($asistencias)->map(function($asistencia, $k) {
                if($k == 1) {
                    $this->assistance = count($asistencia);
                } else {
                    $this->absences = count($asistencia);
                } 
            });


            collect($assignments)->map(function($assignment, $k) {
                $assignment = collect($assignment)->values()->first();
                switch ($assignment->assignments->assignmenttype->name) {
                    case 'Tarea':
                        $this->tasks++;
                        break;
                    case 'Evaluación':
                        $this->exams++;
                        break;
                    case 'Trabajo Practico':
                        $this->tps++;
                        break;   
                }    
            });

        } catch (\Throwable $th) {
            //throw $th;
        }
        
        return [
            'classes' => count($classes),
            'exams' =>  $this->exams,
            'tasks' =>  $this->tasks,
            'tps' =>  $this->tps,
            'assistance' => $this->assistance,
            'absences' => $this->absences,
        ];
    }

    public function teacherDashboard($user) {
        return $user;
    }

    public function institutionDashboard($user) {
        return $user;
    }

    public function adminDashboard($user) {
        return $user;
    }


}
