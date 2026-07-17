<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\Table\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                "name" => "required|string|max:255",
                "capacity" => "required|integer",
                "status" => "required|string|max:255",
                "location" => "nullable|string|max:255",
            ]);

            $table = Table::create($validate);

            return response()->json([
                'message' => 'Table created successfully',
                'data' => $table
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $table = Table::findOrFail($id);

            $validate = $request->validate([
                "name" => "required|string|max:255",
                "capacity" => "required|integer",
                "status" => "required|string|max:255",
                "location" => "required|string|max:255",
            ]);

            $table->update($validate);

            return response()->json([
                'message' => 'Table updated successfully',
                'data' => $table
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();

            return response()->json([
                'message' => 'Table deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $table = Table::findOrFail($id);

            return response()->json([
                'data' => $table
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index()
    {
        try {
            $tables = Table::all();

            return response()->json([
                'data' => $tables
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
