<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    
    public function createCategory(REQUEST $request)
    {
        if ($request->isMethod('post')) {
            $category = new Category();
            $category->name = $request->categoryName;
            $category->description = $request->categoryDescription;
            $category->save();

            return redirect()->route('category.show')->with('success', 'Category added successfully!');
        }
        return view('category.create');
    }

    public function showCategory(REQUEST $request)
    {
        $categories = Category::all();
        return view('category.show' , compact('categories'));
    }
}

