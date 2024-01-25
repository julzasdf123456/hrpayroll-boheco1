<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Biometrics;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * BIOMETRICS
 */
Route::get('get-users', [Biometrics::class, 'getUsers']);
Route::get('get-attendance', [Biometrics::class, 'getAttendance']);
Route::get('get-version', [Biometrics::class, 'getVersion']);
