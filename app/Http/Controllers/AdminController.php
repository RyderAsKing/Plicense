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
}
