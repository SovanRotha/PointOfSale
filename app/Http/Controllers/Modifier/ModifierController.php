<?php

namespace App\Http\Controllers\Modifier;

use App\Http\Controllers\Controller;
use App\Models\Modifier\Modifier;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    //
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         "menu_item_id" => "required|integer|exists:menu_items,id",
    //         "name" => "required|string|max:255",
    //         "price" => "required|numeric",
    //     ]);

    //     $modifier = Modifier::create($validate);

    //     return response()->json([
    //         'message' => 'Modifier created successfully',
    //         'data' => $modifier
    //     ], 201);
    // }

    public function update(Request $request, $id)
    {
        try {
            $modifier = Modifier::findOrFail($id);

            $validate = $request->validate([
                "menu_item_id" => "required|integer|exists:menu_items,id",
                "name" => "required|string|max:255",
                "price" => "required|numeric",
            ]);

            $modifier->update($validate);

            return response()->json([
                'message' => 'Modifier updated successfully',
                'data' => $modifier
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $modifier = Modifier::findOrFail($id);
            $modifier->delete();

            return response()->json([
                'message' => 'Modifier deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $modifier = Modifier::with('menuItem')->findOrFail($id);

            return response()->json([
                'data' => $modifier
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index()
    {
        try {
            $modifiers = Modifier::with('menuItem')->get();

            return response()->json([
                'data' => $modifiers
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
