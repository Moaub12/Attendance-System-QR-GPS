<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Qr;
use App\Http\Controllers\Api\Std;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('getScannedInfo', [Qr::class, 'getScannedInfo']);
Route::post('login', [Auth::class, 'login'])->name('login');
Route::get('/user/{user_id}/courses-attendance', [Std::class, 'getCoursesAndAttendanceCount']);
