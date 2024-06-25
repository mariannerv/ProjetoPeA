<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\notifMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationsController extends Controller
{
    public function getUserDetails($uid)
{
    $user = User::find($uid); 

    $debug_messages = ["Fetched user details for: " . $user->email];

    return response()->json([
        'status' => true,
        'user' => [
            'id' => $user->_id, 
            'name' => $user->name,
            'email' => $user->email
        ],
        'debug_messages' => $debug_messages
    ]);
}


public function fetchAllNotifications(Request $request)
{
    $userId = $request->user_id;

    $debug_messages = ["Fetching notifications for user ID: " . $userId];

    $user_notifications = Notification::where('user_id', $userId)->get();

    if ($user_notifications->isEmpty()) {
        // Create a simple notification if no notifications are found
        $simple_notification = new Notification();
        $simple_notification->user_id = $userId;
        $simple_notification->message = "No notifications found.";
        $simple_notification->save();

        $debug_messages[] = "Created simple notification: " . $simple_notification->toJson();

        // Return the newly created simple notification
        return response()->json([
            'status' => true,
            'data' => [$simple_notification], // Wrap in array to keep JSON format consistent
            'debug_messages' => $debug_messages
        ]);
    }

    $debug_messages[] = "Fetched notifications: " . $user_notifications->toJson();

    return response()->json([
        'status' => true,
        'data' => $user_notifications,
        'debug_messages' => $debug_messages
    ]);
}


    public function sendBidUpdatedNotification(Request $request)
    {
        $userId = $request->user_id;

        $user = User::find($userId);
        $debug_messages = ["User authenticated: " . $user->email];

        $notification = Notification::create([
            'user_id' => $user->_id,
            'type' => 'BidUpdated',
            'data' => ['message' => 'Seu lance foi atualizado', 'auction_id' => $request->auctionId],
        ]);

        $debug_messages[] = "Created notification: " . $notification->toJson();

        $this->sendnotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $previousBidderEmail = $request->previousBidderEmail;
        $user = User::where('email', $previousBidderEmail)->first();
        $debug_messages = ["Previous bidder found: " . $user->email];

        $notification = $user->notifications()->create([
            'user_id' => $user->_id,
            'type' => 'BidOvertaken',
            'data' => ['message' => 'Seu lance foi ultrapassado', 'auction_id' => $request->auctionId],
        ]);

        $debug_messages[] = "Created notification: " . $notification->toJson();

        $this->sendnotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    public function sendTestNotification(Request $request)
    {
        $userId = $request->user_id;

        $user = User::find($userId);
        $debug_messages = ["User authenticated: " . $user->email];

        $notification = $user->notifications()->create([
            'user_id' => $user->_id,
            'type' => 'Test',
            'data' => ['message' => 'Esta é uma notificação de teste'],
        ]);

        $debug_messages[] = "Created notification: " . $notification->toJson();

        $this->sendnotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    private function sendnotifMail($email, $notification)
    {
        Mail::to($email)->send(new notifMail($notification));
    }
}
