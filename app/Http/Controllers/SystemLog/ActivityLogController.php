<?php

namespace App\Http\Controllers\SystemLog;

use App\Http\Controllers\Controller;
use App\Models\SystemLog\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        try {
            $logs = ActivityLog::with('user')->latest()->get();

            return response()->json([
                'data' => $logs,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $log = ActivityLog::with('user')->findOrFail($id);

            return response()->json([
                'data' => $log,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
