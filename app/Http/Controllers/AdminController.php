<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\License;
use Illuminate\Http\Request;

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
        $this->validate($request, ['user_id' => 'nullable']);

        if ($request->user_id == "null") {
            $this->validate($request, ['email' => 'required|email', 'password' => 'required|min:8']);
        }
    }
}
