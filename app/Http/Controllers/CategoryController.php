<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;

class CategoryController extends Controller
{
    public function show(Request $request)
    {
        return CategoryResource::collection(Category::whereNull('category_id')->with('children')->get());
    }
}
