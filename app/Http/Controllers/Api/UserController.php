<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {
        //
        $validator = Validator::make($request->all(), ['name' => 'required|string', 'email' => 'required|email|unique:users,email', 'password' => 'required|string|min:8', 'type' => 'required|integer|min:0|max:1']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        } else {
            $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'type' => $request->type]);
            if ($user) {
                return response()->json(['success' => 'User created successfully']);
            }
        }
    }
}
