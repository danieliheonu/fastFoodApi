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
