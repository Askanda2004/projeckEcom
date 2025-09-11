<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::select('category_id','category_name')->latest()->take(8)->get();
        $featured   = Product::latest('product_id')->take(10)->get();
        return view('home', compact('categories','featured'));
    }
}