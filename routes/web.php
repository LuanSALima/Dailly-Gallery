<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::view('/', 'site.layout')->name('home');

Route::get('login', [App\Http\Controllers\UserController::class, 'showLoginForm'])->name('user.login');

Route::get('registrar', [App\Http\Controllers\UserController::class, 'showRegisterForm'])->name('user.register');

Route::post('register/do', [App\Http\Controllers\UserController::class, 'register'])->name('user.register.do');

Route::post('login/do', [App\Http\Controllers\UserController::class, 'login'])->name('user.login.do');

Route::post('logout', [App\Http\Controllers\UserController::class, 'logout'])->name('user.logout');