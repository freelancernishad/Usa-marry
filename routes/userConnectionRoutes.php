<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Api\User\Connection\UserConnectionController;



Route::middleware(AuthenticateUser::class)->prefix('user')->group(function () {
    // Send a connection request to another user
    Route::post('/connection/{connectedUserId}/connect', [UserConnectionController::class, 'connectWithUser']);

    // Accept a connection request
    Route::post('/connection/{connectedUserId}/accept', [UserConnectionController::class, 'acceptConnection']);

    // Disconnect from a user (remove the connection)
    Route::post('/connection/{connectedUserId}/disconnect', [UserConnectionController::class, 'disconnectFromUser']);

    // Reject from a user (remove the connection)
    Route::post('/connection/{UserId}/reject', [UserConnectionController::class, 'rejectConnectionRequest']);
    Route::get('/connection-reject/list', [UserConnectionController::class, 'getRejectedConnections']);

    // Cancel from a user (remove the connection)
    Route::post('/connection/{connectedUserId}/cancel', [UserConnectionController::class, 'cancelFromUser']);


    Route::get('/connection-canceled/list', [UserConnectionController::class, 'getCancelledConnections']);



    // Get all accepted connections
    Route::get('/connections', [UserConnectionController::class, 'getConnections']);

    // Get all pending connections
    Route::get('/pending-connections', [UserConnectionController::class, 'getPendingConnections']);

         // Get all disconnected users for the authenticated user
    Route::get('/disconnected-users', [UserConnectionController::class, 'getDisconnectedUsers']);

    // Get all users who have connected with the current user
    Route::get('/users-who-connected-with-me', [UserConnectionController::class, 'getUsersWhoConnectedWithMe']);

    // Get all users to whom the current user has sent a connection request that was accepted
    Route::get('/my-sent-accepted-connections', [UserConnectionController::class, 'getMySentAcceptedConnections']);

    // Get all pending connections for the current user
    Route::get('/pending-connections-for-me', [UserConnectionController::class, 'getPendingConnectionsForMe']);


});
