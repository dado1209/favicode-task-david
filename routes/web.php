<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\isAuthenticated;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['isAuthenticated'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});



Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login.get');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/login', 'postLogin')->name('login.post');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'postRegister')->name('register.post');
});

