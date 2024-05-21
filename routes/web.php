<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

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

Route::post('/business', [BusinessController::class, 'store']);
Route::put('/business/{id}', [BusinessController::class, 'update']);
Route::delete('/business/{id}', [BusinessController::class, 'destroy']);
Route::get('/business/search', [BusinessController::class, 'search']);
Route::get('/', function () {
    return view('welcome');
});
