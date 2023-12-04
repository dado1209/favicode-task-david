<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

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


Route::middleware(['auth'])->group(function () {
    Route::controller(FileController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/upload', 'showUploadForm')->name('showUpload');
        Route::post('/upload', 'upload')->name('upload');
        Route::get('/download/{id}', 'download')->name('file.download');
        Route::post('/files/{id}', 'update')->name('file.update');
        Route::delete('/files/{id}', 'delete')->name('file.delete');

    });

});



Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login.get');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/login', 'authenticate')->name('login.post');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('register.post');
});

