<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;

class UserController extends Controller
{
    /**
     * @OA\Get(
     ** path="/users",
     *   tags={"User"},
     *   summary="Get All Users",
     *   operationId="listUsers",
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
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
     * @OA\Get(
     **  path="/user/{id}",
     *   tags={"User"},
     *   security={{"bearer_token":{}}},
     *   summary="Get User Details",
     *   operationId="userDetail",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="user not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
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
     * @OA\Put(
     **  path="/user/{id}",
     *   tags={"User"},
     *   security={{"bearer_token":{}}},
     *   summary="Update User Details",
     *   operationId="updateUserDetail",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=false,
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
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully updated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="user not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
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
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'password'=> bcrypt($request->password)
        
        ]);
        return response()->json([
            "status" => 200,
            "data" => $user,
            "message" => "successfully updated"
        ]);
    }

    /**
     * @OA\Delete(
     **  path="/user/{id}",
     *   tags={"User"},
     *   security={{"bearer_token":{}}},
     *   summary="Delete User Details",
     *   operationId="deleteUserDetail",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="user successfully deleted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="user not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
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

    /**
     * @OA\Get(
     **  path="/user/{id}/restaurants",
     *   tags={"User"},
     *   security={{"bearer_token":{}}},
     *   summary="Get Restaurants Of A User",
     *   operationId="userRestaurantList",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="data not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     */
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

    /**
     * @OA\Get(
     **  path="/user/{id}/favourite",
     *   tags={"User"},
     *   security={{"bearer_token":{}}},
     *   summary="Get Favourite Restaurants Of A User",
     *   operationId="userFavouriteRestaurant",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function userFavouriteRestaurant(Request $request, $id)
    {
        $user = User::find($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $user->favouriteRestaurant
        ]);
    }
}
