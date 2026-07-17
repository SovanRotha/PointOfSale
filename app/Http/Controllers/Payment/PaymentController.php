<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function store(Request $request)
    {
        try {

        $data = $request->validate([

            'order_id' => 'required|exists:orders,id',

            'payment_method' => 
            'required|in:cash,credit_card,debit_card,mobile_payment,other',

            'amount' => 'required|numeric|min:0',

            'transaction_reference' => 
            'nullable|string',

        ]);



        $payment = DB::transaction(function () use ($data) {


            $order = Order::findOrFail(
                $data['order_id']
            );


            // Check payment amount

            if ($data['amount'] < $order->total) {

                throw new \Exception(
                    'Payment amount is not enough'
                );

            }


            // Calculate change

            $change = 
                $data['amount'] - $order->total;



            $payment = Payment::create([

                'order_id' => $order->id,

                'payment_method' =>
                    $data['payment_method'],

                'amount' =>
                    $data['amount'],


                'change_amount' =>
                    $change,


                'transaction_reference' =>
                    $data['transaction_reference'] ?? null,


                'status' =>
                    'completed',


                'paid_by' =>
                    Auth::id(),

            ]);



            // Update order status

            $order->update([

                'status' => 'paid'

            ]);



            return $payment;

        });



        return response()->json([

            'message' =>
                'Payment completed successfully',

            'data' =>
                $payment->load('order')

        ],201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }

    }



    public function index()
    {
        try {
            $payments = Payment::with([
                'order',
                'paid_by'
            ])
            ->latest()
            ->paginate(20);

            return response()->json([

                'message'=>
                    'Retrieved payments',

                'data'=>$payments

            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }

    }



    public function show(Payment $payment)
    {
        try {
            return response()->json([

                'data'=>$payment->load([
                    'order',
                    'paid_by'
                ])

            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }

    }

}