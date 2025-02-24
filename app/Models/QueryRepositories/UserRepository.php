<?php

namespace App\Models\QueryRepositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    // Register a new user
    public function register($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Login user
    public function login($credentionals)
    {
        if (Auth::attempt($credentionals)) {
            $user = Auth::user();
            $user->createToken('token')->plainTextToken;
            return $user;
        }
    }

    // Get user by email for login
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    // Get user by id (for profile fetching or updating)
    public function getUserById($id)
    {
        return User::find($id);
    }
}
