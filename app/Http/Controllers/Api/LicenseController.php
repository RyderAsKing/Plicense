<?php

namespace App\Http\Controllers\Api;

use App\Models\License;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LicenseController extends Controller
{
    public function verify(Request $request, $key = null)
    {
        $licenseKey = $key ?: $request->input('key');

        if (empty($licenseKey)) {
            return response()->json([
                'valid' => false,
                'message' => 'License key is required',
            ], 422);
        }

        $license = License::where(['key' => $licenseKey])->firstOrFail();
        $response = [];
        $status = 200;

        if ($license->expireable && $license->expires_at && $license->expires_at->isPast()) {
            if ($license->status !== 'Expired') {
                $license->status = 'Expired';
                $license->save();
            }
        }

        if ($license->status != 'Active') {
            $response = ['valid' => false, 'message' => 'License expired', 'expired_on' => $license->expires_at];
            $status = 403;
        } else {
            $expiry = "";
            if ($license->expireable == false) {
                $expiry = "Never";
            } else {
                $expiry = $license->expires_at->format('M d Y');
            }

            if ($license->ip == '') {
                // Atomic first-bind to avoid race conditions on concurrent requests.
                $bound = DB::transaction(function () use ($license, $request) {
                    $fresh = License::where('id', $license->id)->lockForUpdate()->first();
                    if ($fresh->ip !== '') {
                        return $fresh;
                    }
                    $fresh->ip = $request->ip();
                    $fresh->save();
                    return $fresh;
                });

                if ($bound->ip === $request->ip()) {
                    $response = ['valid' => true, 'message' => 'License IP bounded to ' . $request->ip(), 'expires_on' => $expiry];
                } else {
                    $response = ['valid' => false, 'message' => 'License is valid however the IP is not same as the one its locked to'];
                    $status = 403;
                }
            } else {
                if ($license->ip == $request->ip()) {
                    $response = ['valid' => true, 'expires_on' => $expiry];
                } else {
                    $response = ['valid' => false, 'message' => 'License is valid however the IP is not same as the one its locked to'];
                    $status = 403;
                }
            }
        }

        return response()->json($response, $status);
    }
}
