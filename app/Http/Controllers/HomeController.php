<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function licenses()
    {
        $licenses = License::where('user_id', '=', Auth::user()->id)->get();
        return view('licenses', ['licenses' => $licenses]);
    }
}
