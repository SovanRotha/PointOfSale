<?php

namespace App\Http\Controllers\SystemLog;

use App\Http\Controllers\Controller;
use App\Models\SystemLog\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        try {
            $settings = Setting::all();

            return response()->json([
                'data' => $settings,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show($id)
    {
        try {
            $setting = Setting::findOrFail($id);

            return response()->json([
                'data' => $setting,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
