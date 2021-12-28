<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validation = [
            "name" => "required",
            "email" => "required|email",
            "password" => "required|confirmed|min:6" // this is the field for comparing pasword "password_confirmation"
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()){
            return response()->json([
                "status" => 400,
                "data" => [],
                "message" => $validator->errors()
            ]);
        }

        // $user = User::create($request->input()); //here is the issue 
        //lets leave the code like this so that we can be able to hash the password 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'password'=> bcrypt($request->password)
        
        ]);
        return response()->json([
            "status" => 201,
            "data" => $user,
            "message" => "user successfully created"
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
            
        if (Auth::attempt($data)){
            return response()->json([
                "status" => 200,
                "data" => $request->user(),
                "message" => "successfully logged in",
                "token" => auth()->user()->createToken('API Token')->plainTextToken
            ]);
        }
        
        return response()->json([
            "status" => 400,
            "data" => $data,
            "message" => "Incorrect. Check your email or password"
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 200,
            "data" => [],
            "message" => "user successfully logged out"
        ]);
    }

}
