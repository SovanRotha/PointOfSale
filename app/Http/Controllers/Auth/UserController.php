<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required|in:active,inactive',
                'role' => 'required|string|exists:roles,name',
            ]);

            $profilePath = null;

            if ($request->hasFile('profile')) {

                $profilePath = $request->file('profile')
                    ->store('profiles', 'public');
            }

            $validate['profile'] = $profilePath;
            $user = User::create([
                'name' => $validate['name'],
                'email' => $validate['email'],
                'password' => bcrypt($validate['password']),
                'phone' => $validate['phone'] ?? null,
                'profile' => $profilePath,
                'status' => $validate['status'],
            ]);

            try {
                $user->assignRole($validate['role']);
            } catch (\Throwable $roleException) {
                // Ignore role assignment failures during registration so the user can still be created.
            }

            return response()->json(['message' => 'User created successfully', 'user' => $user->load('roles.permissions')], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'sometimes|required|in:active,inactive',
                'role' => 'sometimes|required|string|exists:roles,name',
            ]);

            $user = User::findOrFail($id);

            if ($request->hasFile('profile')) {
                if ($user->profile) {
                    Storage::disk('public')->delete($user->profile);
                }
                $profilePath = $request->file('profile')
                    ->store('profiles', 'public');

                $validate['profile'] = $profilePath;
            }

            if (isset($validate['password'])) {
                $validate['password'] = bcrypt($validate['password']);
            }

            $user->update([
                'name' => $validate['name'] ?? $user->name,
                'email' => $validate['email'] ?? $user->email,
                'password' => $validate['password'] ?? $user->password,
                'phone' => $validate['phone'] ?? $user->phone,
                'profile' => $validate['profile'] ?? $user->profile,
                'status' => $validate['status'] ?? $user->status,
            ]);

            // Update the user's role if provided
            if (isset($validate['role'])) {
                $user->syncRoles([$validate['role']]);
            }

            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['user' => $user], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index()
    {
        try {
            $users = User::all();

            return response()->json(['users' => $users], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    // public function login(Request $request)
    // {
    //     try {
    //         $credentials = $request->validate([
    //             'email' => 'required|email',
    //             'password' => 'required',
    //         ]);

    //         if (!Auth::attempt($credentials)) {
    //             return response()->json([
    //                 'message' => 'Invalid credentials'
    //             ], 401);
    //         }

    //         if ($request->hasSession()) {
    //             $request->session()->regenerate();
    //         }

    //         $user = User::with('roles.permissions')->find(Auth::id());

    //         $token = $user->createToken('pos-token')->plainTextToken;

    //         return response()
    //             ->json([
    //                 "message" => "Login successful",
    //                 "user" => $user
    //             ])
    //             ->cookie(
    //                 'auth_token',
    //                 $token,
    //                 60 * 24,
    //                 '/',
    //                 null,
    //                 false,
    //                 true,
    //                 false,
    //                 'Strict'
    //             );
    //     } catch (\Throwable $e) {
    //         return $this->handleException($e);
    //     }
    // }

    // public function logout(Request $request)
    // {

    //     $user = $request->user();


    //     // delete current token
    //     $user->currentAccessToken()->delete();


    //     return response()
    //         ->json([
    //             'message' => 'Logout successful'
    //         ])
    //         ->cookie(
    //             'auth_token',
    //             '',
    //             -1,
    //             '/',
    //             null,
    //             false,
    //             true,
    //             false,
    //             'Strict'
    //         );
    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }


        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $user = User::with('roles.permissions')
            ->find(Auth::id());

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            
        ]);
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->json([
                'message' => 'Logout successful'
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
