<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Item\MenuItem;
use App\Models\Modifier\Modifier;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderItemModifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        try {

        $data = $request->validate([

            'table_id' => 'nullable|exists:tables,id',

            'type' => 'required|in:dine_in,takeaway,delivery',

            'tax' => 'nullable|numeric',

            'discount' => 'nullable|numeric',

            'service_charge' => 'nullable|numeric',

            'notes' => 'nullable|string',

            'user_id' => "nullable|integer",

            'items' => 'required|array|min:1',


            'items.*.menu_item_id'
            => 'required|exists:menu_items,id',

            'items.*.quantity'
            => 'required|integer|min:1',


            'items.*.modifiers'
            => 'nullable|array',


            'items.*.modifiers.*.modifier_id'
            => 'required|exists:modifiers,id',


            'items.*.modifiers.*.quantity'
            => 'required|integer|min:1',

        ]);



        $order = DB::transaction(function () use ($data) {

            $order = Order::create([

                'order_number' =>
                'ORD-' . now()->format('YmdHis')
                    . '-' . rand(1000, 9999),


                'table_id' =>
                $data['table_id'] ?? null,


                'type' =>
                $data['type'],


                'status' =>
                'pending',

            
                'sub_total' => 0,
                'tax' => $data['tax'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'service_charge' => $data['service_charge'] ?? 0,
                'total' => 0,
                'notes' => $data['notes'] ?? null,
            ]);

            $subTotal = 0;


            foreach ($data['items'] as $item) {


                $menuItem = MenuItem::findOrFail(
                    $item['menu_item_id']
                );

                $modifierTotal = 0;


                foreach ($item['modifiers'] ?? [] as $modifierData) {

                    $modifier = Modifier::findOrFail(
                        $modifierData['modifier_id']
                    );


                    $modifierTotal += $modifier->price * $modifierData['quantity'];
                }


                $itemTotal = ($menuItem->selling_price * $item['quantity']) + ($modifierTotal * $item['quantity']);

                $subTotal += $itemTotal;

                $orderItem = OrderItem::create([

                    'order_id' =>
                    $order->id,


                    'menu_item_id' =>
                    $menuItem->id,


                    'quantity' =>
                    $item['quantity'],


                    'unit_price' =>
                    $menuItem->selling_price,


                ]);

                foreach ($item['modifiers'] ?? [] as $modifierData) {


                    $modifier = Modifier::findOrFail(
                        $modifierData['modifier_id']
                    );

                    OrderItemModifier::create([

                        'order_item_id' =>
                        $orderItem->id,


                        'modifier_id' =>
                        $modifier->id,


                        'price' =>
                        $modifier->price,

                        'quantity' => $modifierData['quantity'],

                    ]);
                }
            }
           

            $total = $subTotal + $order->tax - $order->discount + $order->service_charge;

            $order->update([
                'sub_total' => $subTotal,
                'total' => $total,
            ]);
            return $order;
        });

        return response()->json([

            'message' =>
            'Order created successfully',
            'data' =>
            $order->load(
                'items.modifiers'
            )

        ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index(){
        try {
            $orders = Order::with('table', 'items.menuItem', 'items.modifiers', 'user')->latest()->paginate(20);

            return response()->json(
                [
                    "message" => "Retrived all orders",
                    "data" => $orders,
                ]
            );
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    
}
