<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{

    public function fetchAllNotifications(Request $request)
    {
        $user = Auth::user();

        $debug_messages = ["Fetching notifications for user ID: " . $user->id];

        $user_notifications = Notification::where('user_id', $user->id)->get();

        if ($user_notifications->isEmpty()) {
            $simple_notification = new Notification();
            $simple_notification->user_id = $user->id;
            $simple_notification->message = "No notifications found.";
            $simple_notification->save();

            $debug_messages[] = "Created simple notification: " . $simple_notification->toJson();

            return response()->json([
                'status' => true,
                'data' => [$simple_notification],
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
        $user = Auth::user();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'BidUpdated',
            'data' => ['message' => 'Seu lance foi atualizado', 'auction_id' => $request->auctionId],
        ]);

        $debug_messages = ["Created notification: " . $notification->toJson()];

        $this->sendNotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $previousBidderEmail = $request->previousBidderEmail;
        $user = User::where('email', $previousBidderEmail)->firstOrFail();

        $debug_messages = ["Previous bidder found: " . $user->email];

        $notification = $user->notifications()->create([
            'user_id' => $user->id,
            'type' => 'BidOvertaken',
            'data' => ['message' => 'Seu lance foi ultrapassado', 'auction_id' => $request->auctionId],
        ]);

        $debug_messages[] = "Created notification: " . $notification->toJson();

        $this->sendNotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        $debug_messages = ["User authenticated: " . $user->email];

        $notification = $user->notifications()->create([
            'user_id' => $user->id,
            'type' => 'Test',
            'data' => ['message' => 'Esta é uma notificação de teste'],
        ]);

        $debug_messages[] = "Created notification: " . $notification->toJson();

        $this->sendNotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => $debug_messages
        ]);
    }

    private function sendNotifMail($email, $notification)
    {
        Mail::to($email)->send(new NotifMail($notification));
    }
}
