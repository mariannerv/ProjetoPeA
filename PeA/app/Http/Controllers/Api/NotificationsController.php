<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NotifMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationsController extends Controller
{
    public function createNotification($userId, $type, $data)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function sendBidUpdatedNotification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'error' => 'User not authenticated.'
            ], 401);
        }
        
        $notification = $this->createNotification(
            $user->id,
            'BidUpdated',
            ['message' => 'Seu lance foi atualizado', 'auction_id' => $request->auctionId]
        );

        $this->sendNotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => ["Created notification: " . $notification->toJson()]
        ]);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $user = User::where('email', $request->previousBidderEmail)->firstOrFail();

        $notification = $this->createNotification(
            $user->id,
            'BidOvertaken',
            ['message' => 'Seu lance foi ultrapassado', 'auction_id' => $request->auctionId]
        );

        $this->sendNotifMail($user->email, $notification);

        return response()->json([
            'status' => true,
            'notification' => $notification,
            'debug_messages' => ["Created notification: " . $notification->toJson()]
        ]);
    }

    public function sendTestNotification(Request $request, $uid)
    {
        try {
            $user = User::where('_id', $uid)->firstOrFail();
            $notification = $this->createNotification(
                $user->_id,
                'Test',
                ['message' => 'Esta é uma notificação de teste']
            );

            $this->sendNotifMail($user->email, $notification);

            return response()->json([
                'status' => true,
                'notification' => $notification,
                'debug_messages' => ["Created notification: " . $notification->toJson()]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'User not found or error creating notification.',
                'debug_messages' => ["Exception: " . $e->getMessage()]
            ], 404);
        }
    }

    public function fetchAllNotifications($uid)
    {
        $notifications = Notification::where('user_id', $uid)->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }

    private function sendNotifMail($email, $notification)
    {
        try {
            Mail::to($email)->send(new notifMail($notification));
        } catch (\Exception $e) {
            Log::error('Mail sending error: ' . $e->getMessage());
        }
    }
}

