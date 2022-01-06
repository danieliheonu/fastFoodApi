<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @OA\Post(
     **  path="/category",
     *   tags={"Category"},
     *   summary="Create A Category",
     *   operationId="createCategory",
     * 
     *  @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="category successfully created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function createCategory(Request $request){
        $request->validate([
            "name" => "required|unique:App\Models\Category"
        ]);

        Category::create($request->all());
        return response()->json([
            "status" => 201,
            "message" => "category successfully created"
        ]);
    }

    /**
     * @OA\Get(
     **  path="/category",
     *   tags={"Category"},
     *   summary="Get All Categories",
     *   operationId="viewCategories",
     * 
     *   @OA\Response(
     *      response=200,
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function viewCategories(){
        $categories = Category::all();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $categories
        ]);
    }

    /**
     * @OA\Get(
     **  path="/category/{id}",
     *   tags={"Category"},
     *   summary="Get A Category Detail",
     *   operationId="viewCategory",
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
     *       description="successfully retrieved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="category not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function viewCategory($id){
        $category = Category::find($id);

        if ($category){
            return response()->json([
                "status" => 200,
                "message" => "successfully retrieved",
                "data" => $category
            ]);
        }
        return response()->json([
            "status" => 404,
            "message" => "category not found",
            "data" => []
        ]);
    }

    /**
     * @OA\Put(
     **  path="/category/{id}",
     *   tags={"Category"},
     *   summary="Update A Category",
     *   operationId="updateCategory",
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
     *       description="category could not be updated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function updateCategory(Request $request, $id){
        $request->validate([
            "name" => "unique:App\Models\Category"
        ]);

        $category = Category::find($id);

        if($category){
            $category->update($request->all());
            return response()->json([
                "status" => 200,
                "message" => "successfully updated",
                "data" => $category
            ]);
        }
        return response()->json([
            "status" => 404,
            "message" => "category could not be updated",
            "data" => []
        ]);
    }

    /**
     * @OA\Delete(
     **  path="/category/{id}",
     *   tags={"Category"},
     *   summary="Delete A Category",
     *   operationId="deleteCategory",
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
     *       description="successfully deleted",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function deleteCategory($id){
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            "status" => 200,
            "message" => "successfully deleted",
            "data" => []
        ]);
    }
}
