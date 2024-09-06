<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ChambaController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/updateProfile', [UserController::class, 'update']);
    Route::put('/updatePassword', [UserController::class, 'updatePassword']);
    Route::put('/updateJobs', [UserController::class, 'updateJobs']);
    Route::get('/showJobs', [UserController::class, 'showJobs']);
});

Route::get('/chamba', [ChambaController::class, 'index'])->name('chamba.index');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/chamba', [ChambaController::class, 'store'])->name('chamba.store');
    Route::get('/chamba/{id}', [ChambaController::class, 'show'])->name('chamba.show');
    Route::delete('/chamba/{id}', [ChambaController::class, 'destroy'])->name('chamba.destroy');
    Route::put('/chamba/{id}', [ChambaController::class, 'update'])->name('chamba.update');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/requests', [\App\Http\Controllers\Api\RequestChambaController::class, 'index']);
    Route::post('/requests', [\App\Http\Controllers\Api\RequestChambaController::class, 'store']);
    Route::put('/requests/{id}', [\App\Http\Controllers\Api\RequestChambaController::class, 'updateStatus']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::get('/reviews/{id}', [\App\Http\Controllers\Api\ReviewController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
});

Route::get('/jobs', function() {
    return \App\Models\Job::all();
});
