<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function update(Request $request)
    {
        //
        $token = Str::random(32);
        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
        return back()->with('token', $token);
    }
}
