<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\UsaMarry\Api\User\Photo\PhotoRequestController;

Route::middleware(AuthenticateUser::class)->prefix('photo-requests')->group(function () {
    Route::post('/send/{receiverId}', [PhotoRequestController::class, 'sendRequest']);
    Route::post('/accept/{requestId}', [PhotoRequestController::class, 'acceptRequest']);
    Route::post('/reject/{requestId}', [PhotoRequestController::class, 'rejectRequest']);
    Route::get('/received', [PhotoRequestController::class, 'receivedRequests']);
    Route::get('/sent', [PhotoRequestController::class, 'sentRequests']);
});
