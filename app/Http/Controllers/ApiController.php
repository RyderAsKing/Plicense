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
}
