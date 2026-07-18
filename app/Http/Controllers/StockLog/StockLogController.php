<?php

namespace App\Http\Controllers\StockLog;

use App\Http\Controllers\Controller;
use App\Models\Item\MenuItem;
use App\Models\StockLog\StockLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockLogController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'menu_item_id' => 'required|exists:menu_items,id',
                'quantity_change' => 'required|integer',
                'change_type' => 'required|in:addition,subtraction',
                'reason' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
                'order_id' => 'nullable|exists:orders,id',
                'notes' => 'nullable|string',
            ]);

            $menuItem = MenuItem::findOrFail($data['menu_item_id']);
            $previousStock = $menuItem->stock_quantity;
            $userId = $data['user_id'] ?? Auth::id();

            if (empty($userId)) {
                $userId = User::query()->value('id');
            }

            if (empty($userId)) {
                throw new \RuntimeException('No user available to associate with the stock log.');
            }

            if ($data['change_type'] === 'addition') {
                $menuItem->stock_quantity += $data['quantity_change'];
            } else {
                $menuItem->stock_quantity -= $data['quantity_change'];
            }

            $menuItem->save();

            $stockLog = StockLogs::create([
                'menu_item_id' => $menuItem->id,
                'previous_stock' => $previousStock,
                'new_stock' => $menuItem->stock_quantity,
                'quantity_change' => $data['quantity_change'],
                'change_type' => $data['change_type'],
                'reason' => $data['reason'] ?? null,
                'user_id' => $userId,
                'order_id' => $data['order_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            return response()->json([
                'message' => 'Stock updated successfully',
                'stock_log' => $stockLog,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

     public function destroy($id)
    {
        try {
            $log = StockLogs::findOrFail($id);

            $log->delete();

            return response()->json([

                'status'=>true,

                'message'=>'Stock log deleted'

            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }

    }

    public function index(Request $request)
    {
        try {
            $query = StockLogs::with('user', 'menuItem');

            if ($request->filled('menu_item_id')) {
                $query->where('menu_item_id', $request->menu_item_id);
            }

            if ($request->filled('change_type')) {
                $query->where('change_type', $request->change_type);
            }

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            $stockLogs = $query->latest()->paginate(20);

            return response()->json([
                'stock_log' => $stockLogs,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

     public function show($id)
    {
        try {
            $stock_log = StockLogs::with([
                'menuItem',
                'user'
            ])
            ->findOrFail($id);

            return response()->json([
                'status'=>true,
                'data'=>$stock_log,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
