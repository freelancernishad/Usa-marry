<?php

namespace App\Http\Controllers\Api\User\Connection;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserConnection;

class UserConnectionController extends Controller
{
    // Send a connection request
    public function connectWithUser($connectedUserId, Request $request)
    {
        $user = $request->user();

        if ($user->id === $connectedUserId) {
            return response()->json(['message' => 'You cannot connect with yourself.'], 400);
        }

        $user->connectWithUser($connectedUserId);

        return response()->json(['message' => 'Connection request sent.']);
    }

    // Accept a connection request
    public function acceptConnection($connectedUserId, Request $request)
    {
        $user = $request->user();

        // Prevent accepting your own connection request
        if ($user->id === (int)$connectedUserId) {
            return response()->json(['message' => 'You cannot accept your own connection request.'], 400);
        }

        // Find the pending connection where the current user is the recipient
        $connection = UserConnection::where('user_id', $connectedUserId)
            ->where('connected_user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($connection) {
            // Accept the connection
            UserConnection::where('id', $connection->id)
                ->update(['status' => 'accepted']);

            return response()->json(['message' => 'Connection accepted.']);
        }

        return response()->json(['message' => 'Connection request not found or already accepted.'], 404);
    }

    // Disconnect from a user (remove the connection)
    public function disconnectFromUser($connectedUserId, Request $request)
    {
        $user = $request->user();

        $connection = $user->disconnectFromUser($connectedUserId);

        if ($connection) {
            return response()->json(['message' => 'Disconnected from user.']);
        }

        return response()->json(['message' => 'Connection not found.'], 404);
    }

    // Get the list of all accepted connections for the current user
    public function getConnections(Request $request)
    {
        $user = $request->user();

        // Call the getConnections method from the User model to get accepted connections
        $connections = $user->getConnections();

        // Return the accepted connections as a JSON response
        return response()->json($connections);
    }

    // Get the list of all pending connections
    public function getPendingConnections(Request $request)
    {
        $user = $request->user();
        $pendingConnections = $user->getPendingConnections();

        return response()->json($pendingConnections);
    }

    // Get the list of all users who have connected with the current user
    public function getUsersWhoConnectedWithMe(Request $request)
    {
        $user = $request->user();
        $users = $user->getUsersWhoConnectedWithMe();

        return response()->json($users);
    }
    // Get the list of all users who have connected with the current user
    public function getMySentAcceptedConnections(Request $request)
    {
        $user = $request->user();
        $users = $user->getMySentAcceptedConnections();

        return response()->json($users);
    }

    // Get the list of all pending connections for the current user
    public function getPendingConnectionsForMe(Request $request)
    {
        $user = $request->user();
        $pendingConnections = $user->getPendingConnectionsForMe();

        return response()->json($pendingConnections);
    }

    public function getDisconnectedUsers(Request $request)
    {
        $user = $request->user();
        $disconnectedUsers = $user->getDisconnectedUsers();

        return response()->json($disconnectedUsers);
    }
}
