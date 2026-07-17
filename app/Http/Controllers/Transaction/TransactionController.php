<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                'message' => 'Transactions endpoint is ready',
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
