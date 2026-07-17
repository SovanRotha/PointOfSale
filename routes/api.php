<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DiscountLog\DiscountlogController;
use App\Http\Controllers\Item\CategoryController;
use App\Http\Controllers\Item\MenuItemController;
use App\Http\Controllers\Modifier\ModifierController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\OrderItemModifier;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\StockLog\StockLogController;
use App\Http\Controllers\SystemLog\ActivityLogController;
use App\Http\Controllers\SystemLog\NotificationController;
use App\Http\Controllers\SystemLog\SettingController;
use App\Http\Controllers\Table\TableController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Models\Order\Order;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

Route::post('/login', [UserController::class, 'login'])->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('/logout', [UserController::class, 'logout'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/tables', [TableController::class, 'store']);
Route::put('/tables/{id}', [TableController::class, 'update']);
Route::delete('/tables/{id}', [TableController::class, 'destroy']);
Route::get('/tables/{id}', [TableController::class, 'show']);
Route::get('/tables', [TableController::class, 'index']);

Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/menu-items', [MenuItemController::class, 'store']);
Route::put('/menu-items/{id}', [MenuItemController::class, 'update']);
Route::delete('/menu-items/{id}', [MenuItemController::class, 'destroy']);
Route::get('/menu-items/{id}', [MenuItemController::class, 'show']);
Route::get('/menu-items', [MenuItemController::class, 'index']);

Route::post('/modifiers', [ModifierController::class, 'store']);
Route::put('/modifiers/{id}', [ModifierController::class, 'update']);
Route::delete('/modifiers/{id}', [ModifierController::class, 'destroy']);
Route::get('/modifiers/{id}', [ModifierController::class, 'show']);
Route::get('/modifiers', [ModifierController::class, 'index']);

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);

Route::delete('/orderItems', [OrderItemController::class, 'destroy']);
Route::delete('/orderItemModifiers', [OrderItemModifier::class, 'destroy']);

Route::post('/payments', [PaymentController::class, 'store']);
Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/payment/{id}', [PaymentController::class, 'show']);

Route::post('/stock_logs', [StockLogController::class, 'store']);
Route::get('/stock_logs', [StocklogController::class, 'index']);
Route::get('/stock_logs/{id}', [StockLogController::class, 'show']);

Route::post('/discount-logs', [DiscountlogController::class, 'store']);
Route::get('/discount-logs', [DiscountlogController::class, 'index']);
Route::get('/discount-logs/{id}', [DiscountlogController::class, 'show']);

Route::get('/activity-logs', [ActivityLogController::class, 'index']);
Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show']);

Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/{id}', [NotificationController::class, 'show']);

Route::get('/settings', [SettingController::class, 'index']);
Route::get('/settings/{id}', [SettingController::class, 'show']);

Route::get('/transactions', [TransactionController::class, 'index']);

