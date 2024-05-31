namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string|exists:notifications,id',
        ]);

        $user = Auth::user();
        $notification = $user->notifications()->find($request->notification_id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Notification not found.',
        ], 404);
    }

    public function markAsUnread(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string|exists:notifications,id',
        ]);

        $user = Auth::user();
        $notification = $user->notifications()->find($request->notification_id);

        if ($notification) {
            $notification->update(['read_at' => null]);
            return response()->json([
                'status' => true,
                'message' => 'Notification marked as unread.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Notification not found.',
        ], 404);
    }
}
