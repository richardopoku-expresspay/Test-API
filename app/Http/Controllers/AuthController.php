<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //Log::debug('Request to authenticate',['payload' => $request->all()]);
        $user = User::where('email', $request->input('email'))->first();

        if(!$user instanceof User || !Hash::check($request->input('password'), $user->password)){
            //return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'Fail', 'error_code' => 401], 401);
            return response()->json(errorResponse('Invalid credentials.', 401), 401);
        }


        $data['email'] = $user->email;
        $data['name'] =  $user->name;
        $data['token'] =  $user->createToken('App')->accessToken;

        //return response()->json(['data' => $data, 'message' => 'Authentication successful', 'status' => 'Success', 'error_code' => 200], 200);
        return response()->json(successResponse('Authenticated.', 200, $data), 200);
    }
}
