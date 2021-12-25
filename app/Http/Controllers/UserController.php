<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listUsers()
    {
        $users = User::all();
        return response()->json([
            "status" => 200,
            "data" => $users,
            "message" => "successfully retrieved"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $user->update([$request->input()]);
        return response()->json([
            "status" => 200,
            "data" => $user,
            "message" => "successfully updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
