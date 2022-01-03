<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function createCategory(Request $request){
        $request->validate([
            "name" => "required|strings"
        ]);

        Category::create($request->all());
        return response()->json([
            "status" => 201,
            "message" => "category successfully created"
        ]);
    }

    public function viewCategories(){
        $categories = Category::all();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $categories
        ]);
    }

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
            "message" => "no data found",
            "data" => []
        ]);
    }

    public function updateCategory(Request $request, $id){
        $request->validate([
            "name" => "required|string"
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
            "message" => "could not be updated",
            "data" => []
        ]);
    }

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
