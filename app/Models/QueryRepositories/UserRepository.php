<?php

namespace App\Models\QueryRepositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    // Register a new user
    public static function register($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Get user by email for login
    public static function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    // Get user by id (for profile fetching or updating)
    public static function getUserById($id)
    {
        return User::find($id);
    }
}
