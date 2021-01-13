<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;
use Hash;

use App\Models\{ User, Role, RoleUser};
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

    // NOTIFICATIONS
    public function closeNotification($userId, $type, $id) {
        Log::debug(__METHOD__ . ' ' . Helpers::lsi() . " userId: $userId - type: $type - id: $id");
    }

}
