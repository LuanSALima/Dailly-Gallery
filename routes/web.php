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

Route::get('conta/editar', [App\Http\Controllers\UserController::class, 'showEditAccountForm'])->name('account.edit');

Route::get('conta/senha', [App\Http\Controllers\UserController::class, 'showEditPasswordForm'])->name('account.password');

Route::get('perfil/{user}', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');

Route::post('register/do', [App\Http\Controllers\UserController::class, 'register'])->name('user.register.do');

Route::post('login/do', [App\Http\Controllers\UserController::class, 'login'])->name('user.login.do');

Route::post('logout', [App\Http\Controllers\UserController::class, 'logout'])->name('user.logout');

Route::post('arte/async/store', [App\Http\Controllers\ArtController::class, 'asyncStore'])->name('art.async.store');

Route::post('user/async/register', [App\Http\Controllers\UserController::class, 'asyncRegister'])->name('user.async.register');

Route::post('user/async/login', [App\Http\Controllers\UserController::class, 'asyncLogin'])->name('user.async.login');

Route::patch('account/edit/do', [App\Http\Controllers\UserController::class, 'editAccount'])->name('account.edit.do');

Route::patch('account/async/edit', [App\Http\Controllers\UserController::class, 'asyncEditAccount'])->name('account.async.edit');

Route::patch('account/password/edit', [App\Http\Controllers\UserController::class, 'editPassword'])->name('account.password.edit');

Route::patch('account/async/password/edit', [App\Http\Controllers\UserController::class, 'asyncEditPassword'])->name('account.async.password');

Route::resource('arte', 'App\Http\Controllers\ArtController')->names('art')->parameters(['arte' => 'art']);

Route::post('arte/like/{art_id}', [App\Http\Controllers\ArtLikeController::class, 'rate'])->name('art.like');

Route::post('arte/favorite/{art_id}', [App\Http\Controllers\ArtFavoriteController::class, 'favorite'])->name('art.favorite');

Route::post('arte/comment/{art_id}', [App\Http\Controllers\ArtCommentController::class, 'comment'])->name('art.comment.store');

Route::delete('arte/comment/{art_comment}', [App\Http\Controllers\ArtCommentController::class, 'destroy'])->name('art.comment.destroy');