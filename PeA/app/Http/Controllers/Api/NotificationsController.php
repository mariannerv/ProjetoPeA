<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications\NavNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;

class NotificationsController extends Controller
{
    public function fetchAllNotifications(Request $request)
{
    $user = Auth::user();

    if ($user) {
        $notifications = NavNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($notifications->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No new notifications!',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $notifications,
        ]);
    }

    return response()->json(['status' => false, 'message' => 'User not authenticated.'], 401);
}


    public function sendBidUpdatedNotification(Request $request)
    {
        $auctionId = $request->auctionId;
        $highestBid = $request->highestBid;

        $auction = Auction::where('auctionId', $auctionId)->first();
        if (!$auction) {
            return response()->json(['status' => false, 'message' => 'Auction not found.'], 404);
        }

        // Fetch subscribers of the auction
        $subscribers = $auction->users;

        foreach ($subscribers as $subscriber) {
            NavNotification::create([
                'user_id' => $subscriber->id,
                'title' => 'Bid Updated',
                'type' => 'bid_updated',
                'body' => "The highest bid for auction {$auctionId} is now {$highestBid}.",
                'is_read' => false,
                'read_at' => null,
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Bid updated notifications sent.']);
    }

    public function sendBidOvertakenNotification(Request $request)
    {
        $auctionId = $request->auctionId;
        $previousBidderEmail = $request->previousBidderEmail;

        $previousBidder = User::where('email', $previousBidderEmail)->first();
        if ($previousBidder) {
            NavNotification::create([
                'user_id' => $previousBidder->id,
                'title' => 'Bid Overtaken',
                'type' => 'bid_overtaken',
                'body' => "Your bid for auction {$auctionId} has been overtaken.",
                'is_read' => false,
                'read_at' => null,
            ]);

            return response()->json(['status' => true, 'message' => 'Bid overtaken notification sent.']);
        }

        return response()->json(['status' => false, 'message' => 'Previous bidder not found.'], 404);
    }

    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            NavNotification::create([
                'user_id' => $user->id,
                'title' => 'Test Notification',
                'type' => 'test',
                'body' => 'This is a test notification.',
                'is_read' => false,
                'read_at' => null,
            ]);

            return response()->json(['status' => true, 'message' => 'Test notification sent successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'User not authenticated.'], 401);
    }
}
