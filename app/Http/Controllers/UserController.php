<?php

namespace App\Http\Controllers;

use App\Models\QueryRepositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Register a new user
    public function register(Request $request)
    {
        // Check if email is already registered
        $existingUser = $this->userRepository->getUserByEmail($request->email);
        if ($existingUser) {
            return response()->json(['error' => 'Email already taken.'], Response::HTTP_CONFLICT);
        }

        // Register the user
        $data = $request->only(['name', 'email', 'password']);
        $user = $this->userRepository->register($data);

        // Return the response with the newly created user
        return response()->json(['user' => $user], Response::HTTP_CREATED);
    }
    // Login an existing user
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $user = $this->userRepository->login($credentials);
        $token = $user->createToken('token')->plainTextToken;

        if ($user) {
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    // Fetch the authenticated user
    public function me()
    {
        return response()->json(['user' => Auth::user()], Response::HTTP_OK);
    }

    // Logout the authenticated user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
