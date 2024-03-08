<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware'=> 'api'],function($routes){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::post('/logout',[UserController::class,'logout']);
    Route::post('/profile',[UserController::class,'profile']);
    Route::post('/update-profile',[UserController::class,'updateProfile']);
    Route::post('/send-verify-mail/{email}',[UserController::class,'sendVerifyMail']);
    Route::post('/referesh-token',[UserController::class,'refreshToken']);
});


