<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FavouriteController extends Controller
{   
    /**
     * @OA\Post(
     **  path="/restaurant/{id}/favourite",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Add Restaurant To Favourite",
     *   operationId="addFavourite",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="successfully added to favourites",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function addFavourite(Request $request, $id)
    {
        $user = User::find($request->user()->id);
        $user->favouriteRestaurant()->attach($id);

        return response()->json([
            "status" => 201,
            "message" => "successfully added to favourites",
        ]);
    }

    /**
     * @OA\Delete(
     **  path="/restaurant/{id}/favourite",
     *   tags={"Restaurant"},
     *   security={{"bearer_token":{}}},
     *   summary="Remove Restaurant From Favourite",
     *   operationId="deleteProduct",
     * 
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="successfully removed from favourites",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
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
