<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                "category_id" => "required|integer|exists:categories,id",
                "name" => "required|string|max:255",
                "description" => "nullable|string",
                "sku" => "required|string|max:255",
                "barcode" => "nullable|string|max:255",
                "cost_price" => "required|numeric",
                "selling_price" => "required|numeric",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                "is_active" => "required|boolean",
                "track_stock" => "required|boolean",
                "stock_quantity" => "nullable|integer",
                "low_stock_threshold" => "nullable|integer",
            ]);

            $image_path = null;

            if($request->hasFile('image')){
                $validate['image'] = $request->file('image')->store('menu_items', 'public');
            }

            $validate['image'] = $image_path;

            $menuItem = MenuItem::create($validate);

            return response()->json([
                'message' => 'Menu item created successfully',
                'data' => $menuItem
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);

            $validate = $request->validate([
                "category_id" => "required|integer|exists:categories,id",
                "name" => "required|string|max:255",
                "description" => "nullable|string",
                "sku" => "required|string|max:255",
                "barcode" => "nullable|string|max:255",
                "cost_price" => "required|numeric",
                "selling_price" => "required|numeric",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
                "is_active" => "required|boolean",
                "track_stock" => "required|boolean",
                "stock_quantity" => "nullable|integer",
                "low_stock_threshold" => "nullable|integer",
            ]);

            if($request->hasFile('image')){
                if($menuItem->image){
                    Storage::disk('public')->delete($menuItem->image);
                }
                $validate['image'] = $request->file('image')->store('menu_items', 'public');
            }

            $menuItem->update($validate);

            return response()->json([
                'message' => 'Menu item updated successfully',
                'data' => $menuItem
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);

            if($menuItem->image){
                Storage::disk('public')->delete($menuItem->image);
            }

            $menuItem->delete();

            return response()->json([
                'message' => 'Menu item deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $menuItem = MenuItem::with('category', 'modifier')->findOrFail($id);

            return response()->json([
                'data' => $menuItem
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    public function index()
    {
        try {
            $menuItems = MenuItem::with('category', 'modifier')->get();

            return response()->json([
                'data' => $menuItems
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
