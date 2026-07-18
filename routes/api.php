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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StockLog\StockLogController;
use App\Http\Controllers\SystemLog\ActivityLogController;
use App\Http\Controllers\SystemLog\NotificationController;
use App\Http\Controllers\SystemLog\SettingController;
use App\Http\Controllers\Table\TableController;
use App\Http\Controllers\Transaction\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/



Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user', function (Request  $request) {
        return $request->user()->load('roles.permissions');
    });
    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage users')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage tables')->group(function () {
        Route::post('/tables', [TableController::class, 'store']);
        Route::put('/tables/{id}', [TableController::class, 'update']);
        Route::delete('/tables/{id}', [TableController::class, 'destroy']);
    });
    Route::get('/tables', [TableController::class, 'index']);
    Route::get('/tables/{id}', [TableController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage categories')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage menu items')->group(function () {
        Route::post('/menu-items', [MenuItemController::class, 'store']);
        Route::put('/menu-items/{id}', [MenuItemController::class, 'update']);
        Route::delete('/menu-items/{id}', [MenuItemController::class, 'destroy']);
    });
    Route::get('/menu-items', [MenuItemController::class, 'index']);
    Route::get('/menu-items/{id}', [MenuItemController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Modifiers
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage modifiers')->group(function () {
        Route::post('/modifiers', [ModifierController::class, 'store']);
        Route::put('/modifiers/{id}', [ModifierController::class, 'update']);
        Route::delete('/modifiers/{id}', [ModifierController::class, 'destroy']);
    });
    Route::get('/modifiers', [ModifierController::class, 'index']);
    Route::get('/modifiers/{id}', [ModifierController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:create orders')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
    });
    Route::middleware('permission:view orders')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
    });
    /*
    |--------------------------------------------------------------------------
    | Order Items
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage order items')->group(function () {
        Route::delete('/orderItems', [OrderItemController::class, 'destroy']);
        Route::delete('/orderItemModifiers', [OrderItemModifier::class, 'destroy']);
    });
    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:process payments')->group(function () {
        Route::post('/payments', [PaymentController::class, 'store']);
    });
    Route::middleware('permission:view payments')->group(function () {
        Route::get('/payments', [PaymentController::class, 'index']);
        Route::get('/payment/{id}', [PaymentController::class, 'show']);
    });
    /*
    |--------------------------------------------------------------------------
    | Inventory / Stock
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage inventory')->group(function () {
        Route::post('/stock_logs', [StockLogController::class, 'store']);
    });
    Route::middleware('permission:view inventory')->group(function () {
        Route::get('/stock_logs', [StockLogController::class, 'index']);
        Route::get('/stock_logs/{id}', [StockLogController::class, 'show']);
    });
    /*
    |--------------------------------------------------------------------------
    | Discounts
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:apply discounts')->group(function () {
        Route::post('/discount-logs', [DiscountlogController::class, 'store']);
    });
    Route::get('/discount-logs', [DiscountlogController::class, 'index']);
    Route::get('/discount-logs/{id}', [DiscountlogController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Logs
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:view activity logs')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
        Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show']);
    });
    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationController::class, 'show']);
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:manage settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'index']);
        Route::get('/settings/{id}', [SettingController::class, 'show']);
    });
    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission:view transactions')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index']);
    });
    
    Route::middleware(['auth:sanctum', 'permission:manage permissions'])
        ->group(function () {

            Route::get(
                '/permissions',
                [PermissionController::class, 'index']
            );
            Route::post(
                '/permissions',
                [PermissionController::class, 'store']
            );
            Route::delete(
                '/permissions/{id}',
                [PermissionController::class, 'destroy']
            );
            Route::post(
                '/roles/permissions',
                [PermissionController::class, 'assignToRole']
            );
        });
});
