<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {
        //
        $validator = Validator::make($request->all(), ['name' => 'required|string', 'email' => 'required|email', 'password' => 'required|string|min:8', 'type' => 'required|integer']);
        dd($validator);
    }
}
