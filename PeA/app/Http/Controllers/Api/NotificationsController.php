namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Auction;
use App\Models\User;
use App\Notifications\BidUpdatedNotification;
use App\Notifications\BidOvertakenNotification;
use App\Notifications\TestNotification;

class NotificationsController extends Controller
{
    public function fetchAllNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            $notifications = $user->notifications;

            return response()->json([
                'status' => true,
                'notifications' => $notifications,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching all notifications: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching notifications.',
            ], 500);
        }
    }

    public function sendBidUpdatedNotification(Request $request)
    {
        $auctionId = $request->input('auctionId');
        $userEmail = $request->input('userEmail');

        $auction = Auction::where('auctionId', $auctionId)->first();
        $user = User::where('email', $userEmail)->first();

        if (!$auction || !$user) {
            return response()->json([
                'status' => false,
                'message' => 'Auction or user not found.',
            ], 404);
        }

        $user->notify(new BidUpdatedNotification($auction));

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
        $user = User::where('email', $userEmail)->first();

        if (!$auction || !$user) {
            return response()->json([
                'status' => false,
                'message' => 'Auction or user not found.',
            ], 404);
        }

        $user->notify(new BidOvertakenNotification($auction, $request->input('amount')));

        return response()->json([
            'status' => true,
            'message' => 'Bid overtaken notification sent successfully.',
        ]);
    }

    public function sendTestNotification(Request $request)
    {
        try {
            $user = Auth::user();

            $user->notify(new TestNotification('Your test message'));

            return response()->json([
                'status' => true,
                'message' => 'Test notification sent successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending test notification: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while sending the notification.',
            ], 500);
        }
    }
}
