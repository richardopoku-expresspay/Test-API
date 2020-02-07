<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  RegisterFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterFormRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
      
        $data['email'] = $user->email;
        $data['name'] =  $user->name;
        $data['token'] =  $user->createToken('App')->accessToken;

        return response()->json(successResponse('User account created.', 201, $data), 201);
    } 
}
