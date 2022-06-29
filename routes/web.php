<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CertificateController;

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

Route::get('/', [AuthController::class, 'getMain'])->middleware('auth');

Route::get('/', function () {
    return Redirect::to('login');
})->middleware('auth');

Route::get('login', [AuthController::class, 'getLogin'])->name("login");
Route::post('login', [AuthController::class, 'postLogin']);
Route::get('logout', [AuthController::class, 'getLogout']);
Route::get('signup', [AuthController::class, 'getSignup'])->name("signup");
Route::post('signup', [AuthController::class, 'postSignup']);
Route::get('certificates/{id}', [CertificateController::class, 'getCertificate']);
Route::get('qr/{id}', [CertificateController::class, 'getQr']);

Route::group(['middleware' => 'auth'], function () {

    Route::get('panel/certificates', [CertificateController::class, 'getCertificates'])->middleware('roles:1');
    Route::post('panel/certificates', [CertificateController::class, 'getJson'])->name('certificates.json')->middleware('roles:1');
    Route::post('panel/certificates/add', [CertificateController::class, 'addСertificate'])->name('certificate.add')->middleware('roles:1');
    Route::get('panel/certificates/ajax/id/{id}', [CertificateController::class, 'getCertificaterAjax'])->middleware('roles:1');
    Route::get('panel/certificates/activation', [CertificateController::class, 'CertificaterActivation'])->middleware('roles:1');



    // Users
    Route::get('users', [UsersController::class, 'getUsers'])->middleware('roles:1');
    Route::post('users/getJson', [UsersController::class, 'getJson'])->name('users.json')->middleware('roles:1');
    Route::post('users/add', [UsersController::class, 'addUser'])->name('users.add')->middleware('roles:1');
    Route::get('/users/activation', [UsersController::class, 'usersActivation'])->middleware('roles:1');
    Route::get('/users/ajax/id/{id}', [UsersController::class, 'getUserAjax'])->name('users.ajax.id')->middleware('roles:1');

});


