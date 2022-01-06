<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{

    public function listRestaurants(){
        $restaurants = Restaurant::all();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $restaurants
        ]);
    }

    /**
     * @OA\Post(
     **  path="/restaurant",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Create A Restaurant",
     *   operationId="listRestaurants",
     * 
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="icon",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function createRestaurant(Request $request){
        $request->validate([
            "icon" => "mime:png,jpg",
            "name" => "required"
        ]);

        if($request->icon != NULL)
        {
            Restaurant::create([
                "owner_id" => $request->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "icon" => $request->file('icon')->store('public/images')
            ]);
        }else{
            Restaurant::create([
                "owner_id" => $request->user()->id,
                "name" => $request->name,
                "address" => $request->address,
            ]);
        }

        return response()->json([
            "status" => 201,
            "message" => "successfully created",
            "data" => [
                "owner" => $request->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "icon" => $request->icon
            ]
        ]);
    }

    /**
     * @OA\Get(
     **  path="/restaurant/{id}",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Get A Restaurant Detail",
     *   operationId="restaurantDetail",
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
     *       description="retrieved unsuccessfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function restaurantDetail($id){
        $restaurant = Restaurant::find($id);

        if ($restaurant){
            return response()->json([
                "status" => 200,
                "message" => "successfully retreived",
                "data" => $restaurant
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "retrieved unsuccessfully",
            "data" => []
        ]);
    }

    /**
     * @OA\Put(
     **  path="/restaurant/{id}",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Update A Restaurant Detail",
     *   operationId="updateRestaurant",
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
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *  ),
     *  @OA\Parameter(
     *      name="icon",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ), 
     *   @OA\Response(
     *      response=200,
     *       description="successfully updated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="could not be updated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function updateRestaurant(Request $request, $id){
        $restaurant = Restaurant::find($id);

        $request->validate([
            "icon" => "mime:png,jpg"
        ]);

        if ($restaurant){
            if($request->icon != null){
                $restaurant->update([
                    "owner_id" => $request->user()->id,
                    "name" => $request->name,
                    "address" => $request->address,
                    "icon" => $request->file('icon')->store('public/images')
                ]);
            }else{
                $restaurant->update([
                    "owner_id" => $request->user()->id,
                    "name" => $request->name,
                    "address" => $request->address,
                ]);
            }

            return response()->json([
                "status" => 200,
                "message" => "successfully updated",
                "data" => $restaurant
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "could not be updated",
            "data" => []
        ]);

    }

    /**
     * @OA\Delete(
     **  path="/restaurant/{id}",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Delete A Restaurant",
     *   operationId="deleteRestaurant",
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
     *       description="successfully deleted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="could not be deleted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function deleteRestaurant($id){
        $restaurant = Restaurant::find($id);

        if($restaurant){
            $restaurant->delete();

            return response()->json([
                "status" => 200,
                "message" => "successfully deleted",
                "data" => []
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "could not be deleted",
            "data" => []
        ]);
    }
}
