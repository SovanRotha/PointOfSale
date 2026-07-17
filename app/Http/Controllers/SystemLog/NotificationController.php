<?php

namespace App\Http\Controllers\SystemLog;

use App\Http\Controllers\Controller;
use App\Models\SystemLog\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = Notification::latest()->get();

            return response()->json([
                'data' => $notifications,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $notification = Notification::findOrFail($id);

            return response()->json([
                'data' => $notification,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
