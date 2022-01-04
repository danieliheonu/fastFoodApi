<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Product;

class ProductController extends Controller
{
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

    public function listRestaurantProducts($id){
        $restaurant = Restaurant::find($id);

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $restaurant->product
        ]);
    }

    public function listRestaurantProductCategories($id){
        $restaurant = Restaurant::find($id);
        $categories = [];
        $products = [];

        foreach($restaurant->product as $product)
        {
            array_push($products, Product::where(['id'=>$product->pivot->product_id])->first());
        }

        foreach ($products as $product) {
            $product_category = $product->category->name;
            if(!in_array($product_category, $categories))
            {
                array_push($categories, $product_category);
            }
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
                "data" => $product,
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
