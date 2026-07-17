<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
            ]);

            // $validate['sort_order'] = $validate['sort_order'] ?? 0;
            // $validate['is_active'] = $validate['is_active'] ?? true;

            $category = Category::create($validate);

            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
            ]);

            $category->update($validate);

            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'data' => $category
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    public function index()
    {
        try {
            $categories = Category::with('menuItems')->get();

            return response()->json([
                'data' => $categories
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
