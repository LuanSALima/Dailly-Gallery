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

Route::view('/', 'site.home')->name('home');

Route::get('registrar', [App\Http\Controllers\UserController::class, 'showRegisterForm'])->name('user.register');

Route::get('conta/editar', [App\Http\Controllers\UserController::class, 'showEditAccountForm'])->name('account.edit');

Route::get('conta/senha', [App\Http\Controllers\UserController::class, 'showEditPasswordForm'])->name('account.password');

Route::get('perfil/{user}', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');

Route::post('register/do', [App\Http\Controllers\UserController::class, 'register'])->name('user.register.do');

Route::patch('account/edit/do', [App\Http\Controllers\UserController::class, 'editAccount'])->name('account.edit.do');

Route::patch('account/password/edit', [App\Http\Controllers\UserController::class, 'editPassword'])->name('account.password.edit');

Route::resource('arte', 'App\Http\Controllers\ArtController')->names('art')->parameters(['arte' => 'art']);

Route::post('arte/like/{art_id}', [App\Http\Controllers\ArtLikeController::class, 'rate'])->name('art.like');

Route::post('arte/favorite/{art_id}', [App\Http\Controllers\ArtFavoriteController::class, 'favorite'])->name('art.favorite');

Route::post('arte/comment/{art_id}', [App\Http\Controllers\ArtCommentController::class, 'comment'])->name('art.comment.store');

Route::delete('arte/comment/{art_comment}', [App\Http\Controllers\ArtCommentController::class, 'destroy'])->name('art.comment.destroy');

Route::get('login', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login');

Route::post('login/do', [App\Http\Controllers\LoginController::class, 'login'])->name('login.do');

Route::get('admin/registrar', [App\Http\Controllers\AdminController::class, 'showRegisterForm'])->name('admin.register')->middleware('auth:admin');

Route::post('admin/registrar/do', [App\Http\Controllers\AdminController::class, 'register'])->name('admin.register.do');

Route::post('logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

Route::post('perfil/foto', [App\Http\Controllers\UserController::class, 'changeUserProfilePicture'])->name('profile.pic');

Route::post('perfil/fundo', [App\Http\Controllers\UserController::class, 'changeUserProfileBackground'])->name('profile.bg');

Route::get('requisicao/arte', [App\Http\Controllers\ArtController::class, 'showArtsRequestList'])->name('art.requestlist');

Route::get('requisicao/arte/{art}', [App\Http\Controllers\ArtController::class, 'showArtRequest'])->name('art.request');

Route::patch('arte/mudar-status/{art}', [App\Http\Controllers\ArtController::class, 'artStatusChange'])->name('art.status.change');

Route::get('requisicao/editar-arte/{artChange}', [App\Http\Controllers\ArtChangeController::class, 'showArtEditRequest'])->name('art.requestedit');

Route::patch('editar-arte/mudar-status/{artChange}', [App\Http\Controllers\ArtChangeController::class, 'artStatusChange'])->name('art.requestedit.status.change');

Route::patch('editar-arte/{artChange}/editar', [App\Http\Controllers\ArtChangeController::class, 'update'])->name('art.requestedit.update');

Route::delete('editar-arte/{artChange}/deletar', [App\Http\Controllers\ArtChangeController::class, 'destroy'])->name('art.requestedit.destroy');

Route::get('usuario/seguindo', [App\Http\Controllers\UserController::class, 'showFollowingPage'])->name('user.following')->middleware('auth:user');

Route::post('user/{user}/follow', [App\Http\Controllers\UserFollowController::class, 'follow'])->name('user.follow.do');

Route::get('esqueceu-senha', [App\Http\Controllers\LoginController::class, 'forgotPassword'])->name('forgot.password');

Route::post('recuperar-senha', [App\Http\Controllers\LoginController::class, 'recoverPassword'])->name('recover.password');

//Route::get('mail/test', [App\Mail\RecoverAccount::class, 'build'])->name('mail.recoverpassword');

Route::get('recuperar-conta/{token}', [App\Http\Controllers\LoginController::class, 'showRecoverAccount'])->name('recover.account');

Route::post('recuperar-conta', [App\Http\Controllers\LoginController::class, 'recoverAccount'])->name('recover.account.do');

Route::get('verificar-email/{token}', [App\Http\Controllers\UserController::class, 'verifyEmail'])->name('user.verify.email');