<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderItemModifier as OrderItemModifierModel;
use Illuminate\Http\Request;


class OrderItemModifier extends Controller
{
    //
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         'order_item_id' => 'required|integer|exists:order_items,id',
    //         'modifier_id' => 'required|integer|exists:modifiers,id',
    //         'quantity' => 'required|integer|min:1',
    //     ]);
    //     $validate['price'] = Modifier::findOrFail($validate['modifier_id'])->price;

    //     $orderItemModifier = OrderItemModifierModel::create($validate);

    //     return response()->json([
    //         'message' => 'Order item modifier created successfully',
    //         'data' => $orderItemModifier
    //     ], 201);
    // }

    public function destroy($id){
        try {
            $orderItemModifier = OrderItemModifierModel::find($id);

            $orderItemModifier->delete();

            return response()->json([
                "Message" => "Order Item Modifier has deleted Successfully",
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
