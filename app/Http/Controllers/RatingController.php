<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\User;

class RatingController extends Controller
{
    public function ratings($id){
        $ratings = Restaurant::find($id)->userRating->rating;;
        $sum = 0;
        foreach ($ratings as $key) {
            $sum += $key->rating;
            return $sum/($ratings->count());
        }
    }

    public function rateRestaurant(Request $request, $id){
        $restaurant = Restaurant::find($id);

        if($restaurant){

            $rated = $restaurant->userRating()->where(['user_id'=>$request->user()->id]);

            if($rated != NULL){
                $user = User::find($request->user()->id);
                $user->rateRestaurant()->sync($id, ['rating'=>$request->rating]);
            }else{
                $user = User::find($request->user()->id);
                $user->rateRestaurant()->attach($id, ['rating'=>$request->rating]);
            }

            return response()->json([
                "status" => 200,
                "message" => "successfully rated",
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "restaurant could not be rated",
        ]);
    }

    public function listRatings($id){
        $restaurant = Restaurant::find($id);

        $ratings = $restaurant->userRating;

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $ratings
        ]);
       
    }
}
