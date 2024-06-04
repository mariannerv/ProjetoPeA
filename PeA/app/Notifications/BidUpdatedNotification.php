<?php

namespace App\Notifications;

use App\Models\NotifLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BidUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $auction;

    public function __construct($auction)
    {
        $this->auction = $auction;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'auction_id' => $this->auction->id,
            'content' => 'Your bid has been updated for auction ' . $this->auction->id,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            // Array representation of the notification, if needed
        ];
    }
}
