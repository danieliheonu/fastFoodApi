<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FavouriteController extends Controller
{
    public function addFavourite(Request $request, $id)
    {
        $user = User::find($request->user()->id);
        $user->favouriteRestaurant()->attach($id);

        return response()->json([
            "status" => 201,
            "message" => "successfully added to favourites",
        ]);
    }

    public function removeFavourite(Request $request, $id)
    {
        $user = User::find($request->user()->id);
        $user->favouriteRestaurant()->detach($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully removed from favourites",
        ]);
    }
}
