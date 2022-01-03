<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    
    public function listUsers()
    {
        $users = User::all();
        return response()->json([
            "status" => 200,
            "data" => $users,
            "message" => "successfully retrieved"
        ]);
    }

    public function userDetail($id)
    {
        $user = User::find($id);
        if($user == Null){
            return response()->json([
                "status" => 404,
                "data" => [],
                "message" => "user not found"
            ]);
        }
        return response()->json([
            "status" => 200,
            "data" => $user,
            "message" => "successfully retrieved"
        ]);
    }

    public function updateUserDetail(Request $request, $id)
    {
        $user = User::find($id);
        if($user == Null){
            return response()->json([
                "status" => 404,
                "data" => [],
                "message" => "user not found"
            ]);
        }
        $user->update($request->all());
        return response()->json([
            "status" => 200,
            "data" => $user,
            "message" => "successfully updated"
        ]);
    }

    public function deleteUserDetail($id)
    {
        $user = User::find($id);
        if ($user == Null){
            return response()->json([
                "status" => 404,
                "data" => [],
                "message" => "user not found"
            ]);
        }
        $user->delete();
        return response()->json([
            "status" => 200,
            "data" => [],
            "message" => "user successfully deleted"
        ]);
    }

    public function userRestaurantList($id){
        $user_restaurants = User::find($id)->restaurants;

        if ($user_restaurants){
            return response()->json([
                "status" => 200,
                "message" => "successfully retrieved",
                "data" => $user_restaurants
            ]);
        }

        return response()->json([
            "status" =>  404,
            "message" => "data not found",
            "data" => []
        ]);
    }
}
