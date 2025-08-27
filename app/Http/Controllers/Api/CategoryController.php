<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::all();
    return response()->json($categories);
  }

  public function show(Category $category)
  {
    return response()->json($category);
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    $category = Category::create([
      'name' => $request->name,
      'slug' => Str::slug($request->name),
    ]);

    return response()->json([
      'message' => 'Category created successfully',
      'category' => $category,
    ], 201);
  }

  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    $category->update([
      'name' => $request->name,
      'slug' => Str::slug($request->name),
    ]);

    return response()->json([
      'message' => 'Category updated successfully',
      'category' => $category,
    ]);
  }

  public function destroy(Category $category)
  {
    $category->delete();
    return response()->json(['message' => 'Category deleted successfully']);
  }
}
