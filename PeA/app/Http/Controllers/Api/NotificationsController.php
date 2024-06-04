<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Auction;
use App\Models\User;
use App\Models\NotifLog;
use App\Notifications\BidUpdatedNotification;
use App\Notifications\BidOvertakenNotification;
use App\Notifications\TestNotification;
class NotificationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function fetchAllNotifications(Request $request)
    {
        Log::info('fetchAllNotifications method called.');

        $user = Auth::user();

        if (!$user) {
            Log::error('User not authenticated');
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $notifications = NotifLog::where('user_id', $user->id)->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }
    public function sendBidUpdatedNotification(Request $request)
    {
        $auctionId = $request->input('auctionId');
        $userEmail = $request->input('userEmail');

        $auction = Auction::where('auctionId', $auctionId)->first();
        if (!$auction) {
            return response()->json([
                'status' => false,
                'message' => 'Auction not found.',
            ], 404);
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->notify(new BidUpdatedNotification($auction));

        NotifLog::create([
            'user_id' => $user->_id,
            'content' => 'Bid updated notification sent for auction: '.$auction->auctionId,
            'read' => false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Bid updated notification sent successfully.',
        ]);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $auctionId = $request->input('auctionId');
        $userEmail = $request->input('userEmail');

        $auction = Auction::where('auctionId', $auctionId)->first();
        if (!$auction) {
            return response()->json([
                'status' => false,
                'message' => 'Auction not found.',
            ], 404);
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->notify(new BidOvertakenNotification($auction));

        NotifLog::create([
            'user_id' => $user->_id,
            'content' => 'Bid overtaken notification sent for auction: '.$auction->auctionId,
            'read' => false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Bid overtaken notification sent successfully.',
        ]);
    }

    public function subscribeToAuctionNotifications(Request $request)
    {
        $user = Auth::user();
        $auctionId = $request->input('auctionId');

        $auction = Auction::where('auctionId', $auctionId)->first();

        if (!$auction) {
            return response()->json([
                'status' => false,
                'message' => 'Auction not found.',
            ], 404);
        }

        $user->auctions()->attach($auction->_id);

        return response()->json([
            'status' => true,
            'message' => 'Subscribed to auction notifications successfully.',
        ]);
    }

    public function unsubscribeFromAuctionNotifications(Request $request)
    {
        $user = Auth::user();
        $auctionId = $request->input('auctionId');

        $auction = Auction::where('auctionId', $auctionId)->first();

        if (!$auction) {
            return response()->json([
                'status' => false,
                'message' => 'Auction not found.',
            ], 404);
        }

        $user->auctions()->detach($auction->_id);

        return response()->json([
            'status' => true,
            'message' => 'Unsubscribed from auction notifications successfully.',
        ]);
    }

    public function sendTestNotification(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            Log::error('User not authenticated');
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
                'code' => 401,
            ]);
        }

        // Send the notification
        $user->notify(new TestNotification('Your test message'));

        // Create a NotifLog entry
        NotifLog::create([
            'user_id' => $user->id, // Assuming user_id is stored in the NotifLog
            'content' => 'Your test message', // Assuming content is stored in the NotifLog
            'read' => false, // Assuming read status is initialized as false
        ]);

        Log::info('Test notification sent successfully to user: ' . $user->id);

        return response()->json([
            'status' => true,
            'message' => 'Notification sent successfully',
            'code' => 200,
        ]);
    } catch (\Exception $e) {
        Log::error('Error sending test notification: ' . $e->getMessage());
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while sending the notification.',
            'code' => 500,
        ]);
    }
}


}
