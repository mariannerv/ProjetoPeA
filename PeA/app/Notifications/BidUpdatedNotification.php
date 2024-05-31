
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
class BidUpdatedNotification extends Notification{
    use Queueable;

    protected $auction;
    protected $amount;

    public function __construct($auction, $amount)
    {
        $this->auction = $auction;
        $this->amount = $amount;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Sua licitação foi atualizada.')
            ->line('Novo valor: ' . $this->amount)
            ->line('ID do leilão: ' . $this->auction->auctionId)
            ->line('Data de fim: ' . $this->auction->end_date);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Sua licitação foi atualizada.',
            'auction_id' => $this->auction->auctionId,
            'new_amount' => $this->amount,
            'end_date' => $this->auction->end_date,
        ];
    }
}
