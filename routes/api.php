<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ChambaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/email/verify-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Email verification link sent on your email id']);
    });
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Email has been verified']);
    })->name('verification.verify');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/user/updateProfile', [UserController::class, 'update']);
    Route::post('/user/updatePassword', [UserController::class, 'updatePassword']);
    Route::post('/user/updateJobs', [UserController::class, 'updateJobs']);
    Route::get('/user/showJobs', [UserController::class, 'showJobs']);
    Route::post('/user/updateToWorker', [UserController::class, 'updateToWorker'])->middleware('verified');
    Route::get('/user/{slug}/getWorkerChambas', [UserController::class, 'getWorkerChambas']);
    Route::get('/user/chats', [\App\Http\Controllers\Api\ChatController::class, 'index']);
});

Route::delete('/user/{id}', [UserController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/chat/{uuid}/messages', [\App\Http\Controllers\Api\MessageController::class, 'index']);
    Route::post('/chat/{uuid}/messages', [\App\Http\Controllers\Api\MessageController::class, 'store']);
});

Route::get('/chamba', [ChambaController::class, 'index']);
Route::get('/mostRatedChambas', [ChambaController::class, 'mostRatedChambas']);

Route::get('/chamba/{slug}', [ChambaController::class, 'show'])->name('chamba.show');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/chamba', [ChambaController::class, 'store'])->name('chamba.store');
    Route::delete('/chamba/{id}', [ChambaController::class, 'destroy'])->name('chamba.destroy');
    Route::post('/chamba/{id}', [ChambaController::class, 'update'])->name('chamba.update');
    Route::get('/mychambas', [ChambaController::class, 'myChambas'])->name('chamba.myChambas');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/requests', [\App\Http\Controllers\Api\RequestChambaController::class, 'index']);
    Route::post('/requests', [\App\Http\Controllers\Api\RequestChambaController::class, 'store']);
    Route::get('/requests/{id}', [\App\Http\Controllers\Api\RequestChambaController::class, 'show']);
    Route::put('/requests/{id}', [\App\Http\Controllers\Api\RequestChambaController::class, 'updateStatus']);
    Route::post('/requests/{id}/startChamba', [\App\Http\Controllers\Api\RequestChambaController::class, 'startChamba']);
    Route::post('/requests/{id}/endChamba', [\App\Http\Controllers\Api\RequestChambaController::class, 'endChamba']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationsController::class, 'index']);
    Route::post('/notifications/{id}', [\App\Http\Controllers\Api\NotificationsController::class, 'markAsRead']);
    Route::post('/notifications', [\App\Http\Controllers\Api\NotificationsController::class, 'markAllAsRead']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::get('/reviews/{id}', [\App\Http\Controllers\Api\ReviewController::class, 'index'])->withoutMiddleware(['auth:sanctum']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/images', [\App\Http\Controllers\Api\ImageController::class, 'index']);
    Route::post('/images', [\App\Http\Controllers\Api\ImageController::class, 'store']);
    Route::delete('/images/{id}', [\App\Http\Controllers\Api\ImageController::class, 'destroy']);
});

Route::get('/jobs', function () {
    return \App\Models\Job::all();
});

Route::get('/getUserInfoSlug/{slug}', [UserController::class, 'getUserInfoSlug'])->name('user.getUserInfoSlug');
Route::get('/getJobsBySlug/{slug}', [UserController::class, 'getJobsBySlug'])->name('user.getJobsBySlug');

Route::get('/chambas/{slug}', [ChambaController::class, 'getChambasBySlug'])->name('chamba.getChambasBySlug');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/followers/{id}', [\App\Http\Controllers\Api\FollowController::class, 'getFollowers']);
    Route::post('/follow/{id}', [\App\Http\Controllers\Api\FollowController::class, 'follow']);
    Route::post('/unfollow/{id}', [\App\Http\Controllers\Api\FollowController::class, 'unfollow']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user/getUserFollowingChambas', [ChambaController::class, 'getUserFollowingChambas']);
});

Route::controller(\App\Http\Controllers\Api\WorkerController::class)->group(function () {
    Route::get('/workers', 'index');
});
