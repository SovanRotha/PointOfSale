<?php

namespace App\Http\Controllers\StockLog;

use App\Http\Controllers\Controller;
use App\Models\Item\MenuItem;
use App\Models\StockLog\StockLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockLogController extends Controller
{
    public function store(Request $request)
    {
        try {
        // $validate = $request->validate([
        //     "menu_item_id" => "required|integer|exists:menu_items, id",
        //     "previous_stock" => "nullable|integer",
        //     "new_stock" => "nullable|integer",
        //     "quantity_change" => "required|integer",
        //     "change_type" => "required|in:addition, subtraction",
        //     "reason" => "nullable|string",
        //     "order_id" => "nullable|integer:exists:orders, id",
        //     "notes" => "nullable|string",

        // ]);

        $menu_items = MenuItem::find($request->menu_item_id);

        $oldStock = MenuItem::find($request->stock_quantity);
                    

        if($request->change_type === "addition"){
            $menu_items->stock_quantity +=  $request->quantity_change;
        }
        elseif($request->change_type === "substraction"){
            $menu_items->stock_quantity -= $request->quantity_change;
        }

        $menu_items->save();

        $stock_log = StockLogs::create([
            "menu_item_id" => $request->id,
            "previous_stock" => $request->$oldStock,
            "quantity_change" => $request->quantity,
            "change_tyep" => $request->change_type,
            "new_stock" => $menu_items->stock_quantity,
            "reason" => $request->reason,
            "user_id" => Auth::id(),
            "order_id" => $request->order_id,
            "notes" => $request->notes,
        ]);

        return response()->json([
            "message" => "Stock updated successfully",
            "stock_log" => $stock_log,
        ]);
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

    public function index(Request $request, $query){
        try {
            $stock_log = StockLogs::with('user', 'menuItem');

            if ($request->menu_item) {
                $query->where(
                    'product_id',
                    $request->product_id
                );
            }

            // Filter by type
            if ($request->type) {
                $query->where(
                    'type',
                    $request->type
                );
            }

            // Filter date
            if ($request->date) {
                $query->whereDate(
                    'created_at',
                    $request->date
                );
            }

            $stock_log = $query->latest()->paginate(20);

            return response()->json([
                "stock_log" => $stock_log,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

     public function show($id)
    {
        try {
            $stock_log = StockLogs::with([
                'product',
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
