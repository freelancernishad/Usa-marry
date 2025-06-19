<?php

namespace App\Http\Controllers\Api\User\Connection;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserConnection;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompactUserResource;

class UserConnectionController extends Controller
{

    public function connectWithUser($connectedUserId, Request $request)
    {
        return connectWithUser($connectedUserId);


        // return response()->json(['message' => 'Connection request sent successfully.'], 201);
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
            $connection->status = 'accepted';
            $connection->save();

            $otherUser = User::find($connectedUserId);

            if ($otherUser) {
                // Notify the other user (who sent the request)
                NotificationHelper::sendUserNotification(
                    $otherUser,
                    "{$user->name} has accepted your connection request.",
                    'Connection Accepted',
                    'User',
                    $user->id
                );

                // Notify current user (who accepted)
                NotificationHelper::sendUserNotification(
                    $user,
                    "You have successfully accepted the connection request from {$otherUser->name}. You are now connected.",
                    'Connection Accepted',
                    'User',
                    $otherUser->id
                );
            }

            return response()->json(['message' => 'Connection accepted.']);
        }

        return response()->json(['message' => 'Connection request not found or already accepted.'], 404);
    }

    // Disconnect from a user (remove the connection)
public function disconnectFromUser($connectedUserId, Request $request)
{
    $user = $request->user();

    $connection = $user->connections()
        ->where('connected_user_id', $connectedUserId)
        ->first();

    if ($connection) {
        $connection->status = 'disconnected';
        $connection->save();

        $otherUser = User::find($connectedUserId);

        if ($otherUser) {
            // Notify the other user
            NotificationHelper::sendUserNotification(
                $otherUser,
                "{$user->name} has disconnected from you.",
                'Connection Disconnected',
                'User',
                $user->id
            );

            // Notify the current user
            NotificationHelper::sendUserNotification(
                $user,
                "You have disconnected from {$otherUser->name}.",
                'Connection Disconnected',
                'User',
                $connectedUserId
            );
        }

        return response()->json(['message' => 'Disconnected from user.']);
    }

    return response()->json(['message' => 'Connection not found.'], 404);
}


    // Disconnect from a user (remove the connection)
public function rejectConnectionRequest($requesterId, Request $request)
{
    $user = $request->user();

    // Find the connection where the requester sent the request to the current user
    $connection = \App\Models\UserConnection::where('user_id', $requesterId)
        ->where('connected_user_id', $user->id)
        ->first();

    if ($connection) {
        $connection->status = 'rejected';
        $connection->save();

        $otherUser = User::find($requesterId);

        if ($otherUser) {
            // Notify the requester (other user)
            NotificationHelper::sendUserNotification(
                $otherUser,
                "{$user->name} has rejected your connection request.",
                'Connection Rejected',
                'User',
                $user->id
            );

            // Notify current user
            NotificationHelper::sendUserNotification(
                $user,
                "You have rejected the connection request from {$otherUser->name}.",
                'Connection Rejected',
                'User',
                $otherUser->id
            );
        }

        return response()->json(['message' => 'Rejected from user.']);
    }

    return response()->json(['message' => 'Connection not found.'], 404);
}


public function getRejectedConnections(Request $request)
{
    $user = $request->user();

    $rejectedConnections = \App\Models\UserConnection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('connected_user_id', $user->id);
        })
        ->where('status', 'rejected')
        ->with(['sender.profile', 'receiver.profile'])
        ->get()
        ->map(function ($connection) use ($user) {
            $matchedUser = $connection->user_id == $user->id
                ? $connection->receiver
                : $connection->sender;

             $connection->connection_user = new CompactUserResource($matchedUser);

            unset($connection->sender, $connection->receiver);

            return $connection;
        });

    return response()->json($rejectedConnections);
}




    // Cancel from a user (remove the connection)
    public function cancelFromUser($connectedUserId, Request $request)
    {
        $user = $request->user();

        // Find the connection where the authenticated user connected to $connectedUserId
        $connection = $user->connections()
            ->where('connected_user_id', $connectedUserId)
            ->first();

        if ($connection) {
            $connection->status = 'cancelled';  // status update instead of delete
            $connection->save();

            return response()->json(['message' => 'Connection request cancelled.']);
        }

        return response()->json(['message' => 'Connection not found.'], 404);
    }


    public function getCancelledConnections(Request $request)
    {
        $user = $request->user();

        $cancelledConnections = \App\Models\UserConnection::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('connected_user_id', $user->id);
            })
            ->where('status', 'cancelled')
            ->with(['sender.profile', 'receiver.profile']) // sender/receiver সাথে profile eager load
            ->get()
            ->map(function ($connection) use ($user) {
                $matchedUser = $connection->user_id == $user->id
                    ? $connection->receiver
                    : $connection->sender;

                $connection->connection_user = new CompactUserResource($matchedUser);

                unset($connection->sender, $connection->receiver);

                return $connection;
            });

        return response()->json($cancelledConnections);
    }





    // Get the list of all accepted connections for the current user
    public function getConnections(Request $request)
    {
        $user = $request->user();

        // Get all accepted connections where current user is either sender or recipient
        $connections = \App\Models\UserConnection::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('connected_user_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($connection) use ($user) {
                // Determine the other user in the connection
                if ($connection->user_id == $user->id) {
                    $matchedUser = $connection->receiver;
                } else {
                    $matchedUser = $connection->sender;
                }

                // Set connection_user using UserResource
                $connection->connection_user = new \App\Http\Resources\UserResource($matchedUser);

                // Remove sender and receiver from result to avoid redundancy
                unset($connection->sender, $connection->receiver);

                return $connection;
            });

        return response()->json($connections);
    }


    // Get the list of all pending connections
public function getPendingConnections(Request $request)
{
    $user = $request->user();

    $pendingConnections = \App\Models\UserConnection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id);
                // ->orWhere('connected_user_id', $user->id); // if needed, you can add this later
        })
        ->where('status', 'pending')
        ->with(['sender.profile', 'receiver.profile']) // Eager load sender/receiver with profile
        ->get()
        ->map(function ($connection) use ($user) {
            $matchedUser = $connection->user_id == $user->id
                ? $connection->receiver
                : $connection->sender;
            $connection->connection_user = new CompactUserResource($matchedUser);

            unset($connection->sender, $connection->receiver);

            return $connection;
        });

    return response()->json($pendingConnections);
}


    // Get the list of all users who have connected with the current user
public function getUsersWhoConnectedWithMe(Request $request)
{
    $user = $request->user();

    $users = \App\Models\UserConnection::where('connected_user_id', $user->id) // Current user is the recipient
        ->where('status', 'accepted') // Only accepted connections
        ->with(['sender']) // Load the sender (who sent the request)
        ->get()
        ->map(function ($connection) {
            // Indicate role as 'receiver'
            $connection->role = 'receiver';

            // Set connection_user as the sender
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->sender);

            // Remove sender to avoid duplication
            unset($connection->sender);

            return $connection;
        });

    return response()->json($users);
}

    // Get the list of all users who have connected with the current user
public function getMySentAcceptedConnections(Request $request)
{
    $user = $request->user();

    $users = \App\Models\UserConnection::where('user_id', $user->id) // Current user is the sender
        ->where('status', 'accepted') // Only accepted connections
        ->with(['receiver']) // Load the receiver (who accepted the request)
        ->get()
        ->map(function ($connection) {
            // Indicate role as 'sender'
            $connection->role = 'sender';

            // Set connection_user as the receiver
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->receiver);

            // Remove receiver to avoid duplication
            unset($connection->receiver);

            return $connection;
        });

    return response()->json($users);
}


    // Get the list of all pending connections for the current user
public function getPendingConnectionsForMe(Request $request)
{
    $user = $request->user();

    $pendingConnections = \App\Models\UserConnection::where('connected_user_id', $user->id) // Current user is the recipient
        ->where('status', 'pending') // Only pending connections
        ->with(['sender']) // Load sender info (who sent the request)
        ->get()
        ->map(function ($connection) {
            // Indicate the role
            $connection->role = 'receiver';

            // Set connection_user as the sender
            $connection->connection_user = new \App\Http\Resources\UserResource($connection->sender);

            // Remove sender to avoid redundancy
            unset($connection->sender);

            return $connection;
        });

    return response()->json($pendingConnections);
}

public function getDisconnectedUsers(Request $request)
{
    $user = $request->user();

    $disconnectedUsers = \App\Models\UserConnection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)  // Current user is sender
                  ->orWhere('connected_user_id', $user->id); // or recipient
        })
        ->where('status', 'disconnected') // Only disconnected
        ->with(['sender', 'receiver']) // Eager load both sides
        ->get()
        ->map(function ($connection) use ($user) {
            // Identify the other user
            $matchedUser = $connection->user_id == $user->id
                ? $connection->receiver
                : $connection->sender;

            // Format as connection_user
            $connection->connection_user = new \App\Http\Resources\UserResource($matchedUser);

            // Clean up
            unset($connection->sender, $connection->receiver);

            return $connection;
        });

    return response()->json($disconnectedUsers);
}

}
