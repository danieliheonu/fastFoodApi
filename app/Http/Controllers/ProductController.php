<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Product;

class ProductController extends Controller
{   
    /**
     * @OA\Post(
     **  path="/restaurant/{id}/product",
     *   tags={"Product"},
     *   security={{"bearer_token":{}}},
     *   summary="Create A Product",
     *   operationId="createProduct",
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
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="float"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="product created successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="product could not be created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function createProduct(Request $request, $id){
        $restaurant = Restaurant::find($id);
        
        if($restaurant){
            Product::create([
                "name" => $request->name,
                "price" => $request->price,
                "category_id" => $request->category_id,
            ]);

            $new_product = Product::where(['name'=>$request->name])->first();

            $restaurant->product()->attach($new_product->id);

            return response()->json([
                "status" => 201,
                "message" => "product created successfully",
                "data" => [
                    "name" => $request->name,
                    "price" => $request->price,
                    "category_id" => $request->category_id,
                ]
            ]);
        }
        return resposne()->json([
            "status" => 404,
            "message" => "product could not be created",
            "data" => []
        ]);
    }

    /**
     * @OA\Get(
     **  path="/restaurant/{id}/products",
     *   tags={"Product"},
     *   summary="Get The Products Of A Restaurant",
     *   operationId="listRestaurantProducts",
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
     *  )
     */
    public function listRestaurantProducts($id){
        $restaurant = Restaurant::find($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $restaurant->product
        ]);
    }

    /**
     * @OA\Get(
     **  path="/restaurant/{id}/categories",
     *   tags={"Product"},
     *   summary="Get The Categories Of A Restaurant",
     *   operationId="listRestaurantCategories",
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
     *  )
     */
    public function listRestaurantCategories($id){
        $restaurant = Restaurant::find($id);
        $categories = [];

        foreach($restaurant->product as $key)
        {
            $product_category = Category::find($key->category_id);
            if(!in_array($product_category->name, $categories))
            {
                array_push($categories, ["id"=>$product_category->id,"name"=>$product_category->name]);
            }
        }

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $categories
        ]);
    }

    /**
     * @OA\Get(
     **  path="/product/{id}",
     *   tags={"Product"},
     *   security={{"bearer_token":{}}},
     *   summary="Get Product Detail",
     *   operationId="viewProduct",
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
     *       description="product not found",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function viewProduct($id){
        $product = Product::find($id);

        if($product){
            return response()->json([
                "status" => 200,
                "message" => "successfully retrieved",
                "data" => $product
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "product not found",
            "data" => []
        ]);
    }

    /**
     * @OA\Put(
     **  path="/product/{id}",
     *   tags={"Product"},
     *   security={{"bearer_token":{}}},
     *   summary="Update Product Detail",
     *   operationId="updateProduct",
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
     *  @OA\Parameter(
     *      name="price",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="float"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="product updated successfully",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *       description="product could not be updated",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function updateProduct(Request $request, $id){
        $product = Product::find($id);

        if($product){
            $product->update($request->all());

            return response()->json([
                "status" => 200,
                "message" => "product updated successfully",
                "data" => $product,
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "product could not be updated",
            "data" => [],
        ]);
    }

    /**
     * @OA\Delete(
     **  path="/product/{id}",
     *   tags={"Product"},
     *   security={{"bearer_token":{}}},
     *   summary="Delete A Product",
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
     *       description="successfully delete",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *  )
     */
    public function deleteProduct($id){
        $product = Product::find($id);

        $product->delete();
        
        return response()->json([
           "status" => 200,
            "message" => "successfully deleted",
            "data" => [], 
        ]);
    }

    /**
     * @OA\Get(
     **  path="/category/{id}/product",
     *   tags={"Product"},
     *   security={{"bearer_token":{}}},
     *   summary="Get Products By Category",
     *   operationId="listProductByCategory",
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
     *  )
     */
    public function listProductByCategory($id)
    {
        $category = Category::find($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $category->products
        ]);
    }
}
