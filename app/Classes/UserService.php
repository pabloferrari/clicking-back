<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;
use Hash;
use DB;

use App\Models\{ User, Role, RoleUser, Student, Teacher};
use App\Classes\{Helpers, NotificationService};

class UserService
{

    public function getUsers()
    {
        return User::get();
    }

    public function getUser($id)
    {
        return User::where('id', $id)->first();
    }

    public function createInstitutionUser($data)
    {
        $newUser = $this->createUser($data);
        $role = $this->createRole($newUser, 'institution');
        return $newUser;
    }

    public function createTeacherUser($data)
    {
        $newUser = $this->createUser($data);
        $role = $this->createRole($newUser, 'teacher');
        // CREATE TEACHERS REG
        // $this->teacherService->create();
        return $newUser;
    }

    public function createStudentUser($data)
    {
        $newUser = $this->createUser($data);
        $role = $this->createRole($newUser, 'student');
        // CREATE STUDENTS REG
        // $this->studentService->create();
        return $newUser;
    }

    public function createUser($data)
    {
        $newUser = new User();
        $newUser->name = $data['name'];
        $newUser->email = $data['email'];
        $newUser->description = $data['description'] ?? null;
        $newUser->institution_id = $data['institution_id'];
        $newUser->image = $data['image'] ?? null;
        $newUser->password = Hash::make($data['password']);
        $newUser->save();
        return $newUser;
    }

    public function createRole($user, $role) {
        $role = Role::where('slug', $role)->first();
        $roleUser =new RoleUser();
        $roleUser->user_id = $user->id;
        $roleUser->role_id = $role->id;
        $roleUser->save();
        return $roleUser;
    }

    public function updateUser($id, $data)
    {
        User::where('id', $id)->update($data);
        return User::where('id', $id)->first();
    }

    public function resetPassword($id, $newPassword)
    {
        User::where('id', $id)->update(['password' => Hash::make($newPassword)]);
        return User::where('id', $id)->first();
    }

    public function deleteUser($id)
    {
        return User::where('id', $id)->delete();
    }

    public function getUsersByIds($ids) {
        return User::whereIn('id', $ids)->get();
    }

    public function getUsersInstitution($filter) {
        return User::where('institution_id', Auth::user()->institution_id)
        ->with(['student', 'teacher'])
        ->where(function($query) use ($filter) {
                $query->where('name', 'LIKE', "%$filter%")
                ->orWhere('email', 'LIKE', "%$filter%")
                ->orWhere('description', 'LIKE', "%$filter%");
            })
        ->select('id','name', 'email', 'image')
        ->get();
    }

    public function getUsersByInstitutionId($id) {
        return User::where('institution_id', $id)
        ->with(['student', 'teacher'])
        ->get()->pluck('id');
    }

    public function getUserIdFromStudentId($ids) {
        $users = [];
        $students = Student::with('user')->whereIn('id', $ids)->get();
        foreach($students as $st) {
            $users[] = $st->user->id;
        }
        return $users;
    }

    public function getUserIdFromTeacherId($ids) {
        $users = [];
        $teachers = Teacher::with('user')->whereIn('id', $ids)->get();
        foreach($teachers as $ts) {
            $users[] = $ts->user->id;
        }
        return $users;
    }

    public static function updateAvatar($data, $request)
    {
        DB::beginTransaction();
        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $dataFile = array(
            'model_name' => 'User',
            'model_id'   => Auth::user()->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $resultFile = $handleFilesUploadService->createFile($dataFile);
        $user = User::where('id', Auth::user()->id)->first();
        $user->image = $resultFile->url;
        $user->save();

        $user = User::with(['roles'])->find($user->id);
        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE USER ' . json_encode($resultFile));
            return $user;
        } else {
            DB::rollback();
            return false;
        }
    }

    // NOTIFICATIONS
    // public function createNotification($userId, $data) {
    //     $data['type'] = "meeting";
    //     $data['id'] = '$meeting->id';
    //     $data['url'] = "meeting->url";
    //     $data['viewed'] = false;
    //     $data['finished'] = false;
    //     $notificationService = new NotificationService();
    //     $notificationService->createNotification($data);

    // }

}
