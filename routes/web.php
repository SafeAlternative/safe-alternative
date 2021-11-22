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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account')->middleware('verified');

Route::get('/', [App\Http\Controllers\AcasaController::class, 'index'])->name('acasa');

Route::get('despre-noi', [App\Http\Controllers\DespreNoiController::class, 'index'])->name('despre-noi');
Route::get('echipa', [App\Http\Controllers\EchipaController::class, 'index'])->name('echipa');
Route::get('portofoliu', [App\Http\Controllers\PortofoliuController::class, 'index'])->name('portofoliu');
Route::get('contact', [App\Http\Controllers\ContactFormController::class, 'create'])->name('contact.create');
Route::post('contact', [App\Http\Controllers\ContactFormController::class, 'store'])->name('contact.store');

Route::get('curieri-integrare-woocommerce-fan-urgentcargus-nemo', [App\Http\Controllers\CurieriController::class, 'index'])->name('curieri');
Route::get('fan-integrare-woocommerce', [App\Http\Controllers\CurieriController::class, 'fan'])->name('curieri.fan');
Route::get('cargus-integrare-woocommerce', [App\Http\Controllers\CurieriController::class, 'cargus'])->name('curieri.cargus');
Route::get('nemo-integrare-woocommerce', [App\Http\Controllers\CurieriController::class, 'nemo'])->name('curieri.nemo');
