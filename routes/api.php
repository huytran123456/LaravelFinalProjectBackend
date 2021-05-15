<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth:api'], function () {
    //all other api routes goes here
    //Get user list
//    Route::get('users', [UserController::class, 'index']);
    //Update user
    Route::post('users/id', [UserController::class, 'update']);
    //Get one user
    Route::get('users/id', [UserController::class, 'show']);
    //Upload image
    Route::post('users/uploadImg', [FileController::class, 'upload_image']);
    //Get image
    Route::get('users/getImg', [FileController::class, 'get_image']);
    //Get avatar
    Route::get('users/getInfoUser', [\App\Http\Controllers\UserInfoController::class, 'getInfoUser']);
    //Edit user
    Route::post('users/editInfoUser', [\App\Http\Controllers\UserInfoController::class, 'editInfoUser']);
    //Get order detail
    Route::get('order/getDetail/{id}', [\App\Http\Controllers\OrderController::class, 'getDetail']);
    //Make order
    Route::post('order/makeOrder', [\App\Http\Controllers\OrderController::class, 'makeOrder']);
});
//Get film list
Route::get('film/getList', [\App\Http\Controllers\FilmController::class, 'index']);
// Save user
Route::post('users', [UserController::class, 'store']);
//Social login
Route::post('social_users', [AuthController::class, 'social_login']);
// Delete user
Route::post('users/delete/{id}', [UserController::class, 'destroy']);
//Login
Route::post('auth/login', [AuthController::class, 'login']);
//Gen QRCode
Route::get('users/genQrCode', [QrCodeController::class, 'getQrCode']);
//Get user list
Route::get('users', [UserController::class, 'index']);
//Reset social password
Route::post('resetSocialPassword', [AuthController::class, 'resetSocialPassword']);
//Send mail
Route::post('sendMail', [\App\Http\Controllers\UserInfoController::class, 'sendMail']);
//Confirm passcode
Route::post('changePass', [\App\Http\Controllers\UserInfoController::class, 'changePass']);

