<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Item\MenuItem;
use App\Models\Order\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    //
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         'order_id' => 'required|integer|exists:orders,id',
    //         'menu_item_id' => 'required|integer|exists:menu_items,id',
    //         'quantity' => 'required|integer|min:1',
            
    //     ]);

    //     $menuItem = MenuItem::findOrFail($validate['menu_item_id']);

    //     $validate['price'] = $menuItem->selling_price ;

        
    //     $orderItem = OrderItem::create($validate);

    //     return response()->json([
    //         'message' => 'Order item created successfully',
    //         'data' => $orderItem
    //     ], 201);
    // }

    public function destroy($id){
        try {
            $orderItem = OrderItem::find($id);
            $orderItem->delete();

            return response()->json([
                "message" => "Delete Order Item successfully"
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    

}
