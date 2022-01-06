<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     ** path="/auth/register",
     *   tags={"Auth"},
     *   summary="Register A User",
     *   operationId="register",
     *   
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="User Successfully Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="Bad Request"
     *   )
     *)
     **/
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

    /**
     * @OA\Post(
     ** path="/auth/login",
     *   tags={"Auth"},
     *   summary="Login A User",
     *   operationId="login",
     *   
     *  @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Successfully Logged In",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="Incorrect. Check your email or password"
     *   )
     *)
     **/
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

    /**
     * @OA\Post(
     ** path="/auth/logout",
     *   tags={"Auth"},
     *   security={{"bearer_token":{}}},
     *   summary="Logout A User",
     *   operationId="logout",
     * 
     *   @OA\Response(
     *      response=200,
     *       description="user successfully logged out",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     **/
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 200,
            "data" => [],
            "message" => "user successfully logged out"
        ]);
    }

}
