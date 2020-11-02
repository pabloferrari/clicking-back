<?php

namespace App\Classes;

use App\Models\{ User, Role, RoleUser};
use Log;
use Hash;
use Illuminate\Support\Facades\Auth;

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


}