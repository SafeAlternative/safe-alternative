<?php

use App\Http\Controllers\API\FanController;
use App\Http\Controllers\API\CargusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthApiController;

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
    
        Route::post('fan/getAccount', [FanController::class, 'getAccount']);
        Route::post('fan/getServices', [FanController::class, 'getServices']);      
        Route::post('fan/generateAwb', [FanController::class, 'generateAwb']);
        Route::post('fan/printAwb', [FanController::class, 'printAwb']);
        Route::post('fan/deleteAwb', [FanController::class, 'deleteAwb']);

        Route::post('cargus/login', [CargusController::class, 'login']);
        Route::post('cargus/generateAwb', [CargusController::class, 'generateAwb']);
        Route::post('cargus/printAwb', [CargusController::class, 'printAwb']);
        Route::post('cargus/deleteAwb', [CargusController::class, 'deleteAwb']);

    });


Route::post("login",[AuthApiController::class,'getToken']);