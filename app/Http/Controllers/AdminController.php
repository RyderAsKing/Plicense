<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\License;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //

    public function users()
    {
        $users = User::with('license')->get();
        return view('admin.users', ['users' => $users]);
    }

    public function licenses()
    {
        $licenses = License::with('user')->get();
        return view('admin.licenses', ['licenses' => $licenses]);
    }

    public function licenses_create()
    {
        $users = User::get();
        return view('admin.licenses_create', ['users' => $users]);
    }

    public function licenses_create_store(Request $request)
    {
        $this->validate($request, ['user_id' => 'nullable', 'expires_at' => 'required|numeric']);
        $user = [];
        $key = 'License-' . Str::random(16);
        if ($request->user_id == "null") {
            $this->validate($request, ['name' => 'required|min:4', 'email' => 'required|email|unique:users,email', 'password' => 'required|min:8']);

            $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'type' => 0]);
        } else {
            $user = User::where(['id' => $request->user_id])->firstOrFail();
        }
        $user->license()->create(['key' => $key, 'ip' => '', 'expires_at' => now()->addDays($request->expires_at), 'status' => 'Active']);
        return back()->with('message', 'License successfully created, key is ' . $key);
    }
}
