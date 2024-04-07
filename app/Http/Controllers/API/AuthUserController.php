<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAuth\LoginRequest;
use App\Http\Requests\ApiAuth\RegisterRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name'=>['required','string','max:225'],
        //     'email'=>['required','email','max:225','unique:'.User::class],
        //     'password'=>['required','confirmed',Rules\Password::defaults()],
        // ],[],[
        //     'name'=>'Name',
        //     'email'=>'Email',
        //     'password'=>'Password',
        // ]);
        // if ($validator->fails()) {
        //     return ApiResponse::sendResponse(422, 'Register Validation Errors', $validator->errors());
        // }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $data['token'] = $user->createToken($user->name)->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        return ApiResponse::sendResponse(201, 'User Created Successfully', $data);
    }
    public function login(LoginRequest $request)
    {

        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            $user = Auth::user();
            $data['token'] = $user->createToken($user->name)->plainTextToken;
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            return ApiResponse::sendResponse(201, 'User Logging Successfully', $data);
        }else{
            return ApiResponse::sendResponse(401, 'the credentials doesn\'t ', []);
            // return response()->json(['message' => 'Unauthorized'], 401);

        }

    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'logged out successfully');
    }
}
