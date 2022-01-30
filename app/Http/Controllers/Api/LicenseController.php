<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\License;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    //

    public function verify(Request $request, $key)
    {
        $license = License::where(['key' => $key])->firstOrFail();
        $response = [];

        if ($license->status != 'Active') {
            $response = ['valid' => false, 'message' => 'License expired', 'expired_on' => $license->expires_at];
        } else {
            $expiry = "";
            if ($license->expireable == false) {
                $expiry = "Never";
            } else {
                $expiry = $license->expires_at->format('M d Y');
            }
            if ($license->ip == '') {
                $license->ip = $request->ip();
                $license->save();
                $response = ['valid' => true, 'message' => 'License IP bounded to ' . $request->ip(), 'expires_on' => $expiry];
            } else {
                if ($license->ip == $request->ip()) {
                    $response = ['valid' => true, 'expires_on' => $expiry];
                } else {
                    $response = ['valid' => false, 'message' => 'License is valid however the IP is not same as the one its locked to'];
                }
            }
        }
        return response()->json($response);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'days' => 'required|integer|min:0|max:100']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        } else {
            $user = User::firstOrFail(['email' => $request->email]);
            if ($user) {
                if ($request->days == 0) {
                    $expireable = false;
                    $expires_at = now();
                } else {
                    $expireable = true;
                    $expires_at = now()->addDays($request->days);
                }
                $key = 'License-' . Str::random(16);
                $license = $user->license()->create(['key' => $key, 'status' => 'Active', 'ip' => '', 'expires_at' => $expires_at, 'expireable' => $expireable]);
                return response()->json(['success' => 'License created successfully', 'key' => $key]);
            }
        }
    }
}
