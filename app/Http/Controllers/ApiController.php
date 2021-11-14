<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //

    public function index(Request $request, $key)
    {
        $license = License::where(['key' => $key])->firstOrFail();

        if ($license->ip == $request->ip) {
            dd($request->ip());
        }
    }
}
