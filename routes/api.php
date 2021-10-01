<?php

use App\Http\Controllers\API\FanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => 'auth:sanctum'], function(){
    
        Route::get('fan/generateAwb', [FanController::class, 'generateAwb']);
        Route::get('fan/printAwb', [FanController::class, 'printAwb']);

    });


Route::post("login",[UserController::class,'index']);