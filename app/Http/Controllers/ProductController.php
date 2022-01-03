<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Restaurant;
use App\Model\Category;

class ProductController extends Controller
{
    public function createProduct(Request $request, $id){
        $restaurant = Restaurant::find($id);

        $category = Category::where(['name' => $request->category])->first();
        
        if($restaurant){
            Product::create([
                "name" => $request->name,
                "price" => $request->price,
                "category_id" => $category->id,
                "restaurant_id" => $restaurant_id,
            ]);

            return response()->json([
                "status" => 201,
                "message" => "product created successfully",
                "data" => [
                    "name" => $request->name,
                    "price" => $request->price,
                    "category_id" => $category->id,
                    "restaurant_id" => $restaurant_id,
                ]
            ]);
        }
        return resposne()->json([
            "status" => 404,
            "message" => "product could not be created",
            "data" => []
        ]);
    }

    public function listRestaurantProducts($id){
        $restaurant = Restaurant::find($id);
        $products = Product::where(['restaurant_id'=>$restaurant->id])->get();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $products
        ]);
    }

    public function listRestaurantProductCategories($id){
        $restaurant = Restaurant::find($id);
        $categories = [];
        $products = Product::where(['restaurant_id'=>$restaurant->id])->get();

        foreach ($products as $product) {
            $product_category = $product->category->name;
            array_push($categories, $product_category);
        }

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $categories
        ]);
    }

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
            "message" => "no data found",
            "data" => []
        ]);
    }

    public function updateProduct(Request $request, $id){
        $product = Product::find($id);

        $category = Category::where(['name' => $request->category])->first();

        if($product){
            $product->update([
                "name" => $request->name,
                "price" => $request->price,
                "category_id" => $category->id,
            ]);

            return response()->json([
                "status" => 200,
                "message" => "product updated successfully",
                "data" => [
                    "name" => $request->name,
                    "price" => $request->price,
                    "category_id" => $category->id,
                    "restaurant_id" => $restaurant_id,
                ],
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "product could not be updated",
            "data" => [],
        ]);
    }

    public function deleteProduct($id){
        $product = Product::find($id);

        $product->delete();
        
        return response()->json([
           "status" => 202,
            "message" => "successfully deleted",
            "data" => [], 
        ]);
    }
}
