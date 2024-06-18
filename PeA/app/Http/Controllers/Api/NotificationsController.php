<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NotificationEmail;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationsController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->whereNull('read_at')->get();

        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);
    }

    public function sendBidUpdatedNotification(Request $request)
    {
        $user = Auth::user();
        $notification = $user->notifications()->create([
            'type' => 'BidUpdated',
            'data' => ['message' => 'Your bid has been updated', 'auction_id' => $request->auctionId],
        ]);

        Mail::to($user->email)->send(new NotificationEmail($notification));

        return response()->json(['status' => true]);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $user = User::where('email', $request->previousBidderEmail)->first();
        $notification = $user->notifications()->create([
            'type' => 'BidOvertaken',
            'data' => ['message' => 'Your bid has been overtaken', 'auction_id' => $request->auctionId],
        ]);

        Mail::to($user->email)->send(new NotificationEmail($notification));

        return response()->json(['status' => true]);
    }

    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();
        $notification = $user->notifications()->create([
            'type' => 'Test',
            'data' => ['message' => 'This is a test notification'],
        ]);

        Mail::to($user->email)->send(new NotificationEmail($notification));

        return response()->json(['status' => true]);
    }
}
