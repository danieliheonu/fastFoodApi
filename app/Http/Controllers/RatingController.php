<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;

class RatingController extends Controller
{
    public function ratings($id){
        $ratings = Restaurant::find($id)->userRating;
        $sum = 0;
        foreach ($ratings as $key) {
            $sum += $key->pivot->rating;
            return $sum/($ratings->count());
        }
    }

    /**
     * @OA\Post(
     **  path="/restaurant/{id}/rating",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Rate A Restaurant",
     *   operationId="rateRestaurant",
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
     *      name="rating",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *  ),
     * 
     *   @OA\Response(
     *      response=200,
     *       description="rated successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="restaurant does not exist",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *  )
     */
    public function rateRestaurant(Request $request, $id){
        $restaurant = Restaurant::find($id);

        if($restaurant){
            $already_rated = $restaurant->userRating()->where(["user_id"=>$request->user()->id]);
            if(!$already_rated){
                $user = User::find($request->user()->id);
                $user->rateRestaurant()->attach($id, ['rating'=>$request->rating]);
            }else{
                $user = User::find($request->user()->id);
                $user->rateRestaurant()->detach($id);
                $user->rateRestaurant()->attach($id, ['rating'=>$request->rating]);
            }

            return response()->json([
                "status" => 200,
                "message" => "rated successfully",
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "restaurant does not exist",
        ]);
    }

    public function listRatings($id){
        $restaurant = Restaurant::find($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $restaurant->userRating,
            "total" => $this->ratings($id),
        ]);
       
    }
}
