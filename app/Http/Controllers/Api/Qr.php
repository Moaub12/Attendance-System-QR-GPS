<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Qr extends Controller
{
    public function getScannedInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => 'error',
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $request->input('data');
        return response()->json([
            'response' => $data,
            'message' => 'data'
        ], Response::HTTP_OK);
    }
}
