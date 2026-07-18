<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // API-created users are always clients; admin creation is UI-only.
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->type = 0;
        $user->save();

        return response()->json(['success' => 'User created successfully'], 201);
    }

    public function delete($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ((int) $user->type === 1) {
            return response()->json(['error' => 'Admin users cannot be deleted via API'], 403);
        }

        if (Auth::id() === $user->id) {
            return response()->json(['error' => 'You cannot delete your own account'], 403);
        }

        $user->license()->delete();
        $user->delete();

        return response()->json(['success' => 'User deleted successfully']);
    }
}
