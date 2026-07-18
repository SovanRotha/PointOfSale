<?php

use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);