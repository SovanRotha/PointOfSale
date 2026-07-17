<?php

namespace App\Http\Controllers\DiscountLog;

use App\Http\Controllers\Controller;
use App\Models\DiscountLog\Discount_log;
use Illuminate\Http\Request;

class DiscountlogController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'discount_type' => 'required|in:percentage,fixed_amount',
                'amount' => 'required|numeric',
                'reason' => 'nullable|string',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $discount = Discount_log::create($data);

            return response()->json([
                'message' => 'Discount log created successfully',
                'data' => $discount,
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function index()
    {
        try {
            $discounts = Discount_log::with(['order', 'user'])->latest()->get();

            return response()->json([
                'data' => $discounts,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $discount = Discount_log::with(['order', 'user'])->findOrFail($id);

            return response()->json([
                'data' => $discount,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
