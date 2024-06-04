<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\User;
use App\Models\NotifLog; 
use App\Notifications\BidUpdatedNotification;
use App\Notifications\BidOvertakenNotification;
use App\Notifications\TestNotification;

class NotificationsController extends Controller
{

    public function fetchAllNotifications(Request $request)
{
    $user = Auth::user();
    if (!$user) {
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
            'user_id' => $user->id,
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
            'user_id' => $user->id,
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

        $user->auctions()->attach($auction->id);

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

        $user->auctions()->detach($auction->id);

        return response()->json([
            'status' => true,
            'message' => 'Unsubscribed from auction notifications successfully.',
        ]);
    }

    public function sendTestNotification(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        $message = $request->input('message', 'This is a test notification.');

        $user->notify(new TestNotification($message));

        NotifLog::create([
            'user_id' => $user->id,
            'content' => 'Test notification sent',
            'read' => false,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Test notification sent successfully.',
        ]);
    }
}
