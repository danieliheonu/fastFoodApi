<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Rating;

class RatingController extends Controller
{
    public function ratings($id){
        $ratings = Rating::where(['restaurant_id' => $id])->get();
        $sum = 0;
        foreach ($ratings as $key) {
            $sum += $key->rating;
            return $sum/($ratings->count());
        }
    }

    public function rateRestaurant(Request $request, $id){
        $restaurant = Restaurant::find($id);

        if($restaurant){

            $rated = Rating::where(['restaurant_id' => $restaurant->id, 'user_id' => $request->user()->id])->first();

            if($rated){
                $rated->update([
                    "rating" => $request->rating
                ]);
            }else{
                Rating::create([
                    "user_id" => $request->user()->id,
                    "restaurant_id" => $restaurant->id,
                    "rating" => $request->rating
                ]);
            }

            $restaurant->update([
                "ratings" => ratings($restaurant->id)
            ]);

            return response()->json([
                "status" => 200,
                "message" => "successfully rated",
                "data" => $restaurant
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "restaurant could not be rated",
            "data" => []
        ]);
    }

    public function listRatings($id){
        $restaurant = Restaurant::find($id);

        $rating = Rating::where(['restaurant_id' => $restaurant->id])->get();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $rating
        ]);
       
    }
}
