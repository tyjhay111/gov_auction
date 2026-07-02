<?php

namespace App\Notifications;

use App\Models\Auction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionWonNotification extends Notification
{
    use Queueable;

    protected $auction;

    protected $winningAmount;

    public function __construct(Auction $auction, $winningAmount)
    {
        $this->auction = $auction;
        $this->winningAmount = $winningAmount;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You won the auction: {$this->auction->title}")
            ->greeting("Congratulations {$notifiable->name}!")
            ->line("You won the auction for **{$this->auction->title}**")
            ->line('Winning bid: $'.number_format($this->winningAmount, 2))
            ->action('Complete Payment', route('payment.checkout', $this->auction->id))
            ->line('Please complete payment to finalize your purchase.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
            'amount' => $this->winningAmount,
            'message' => "You won the auction \"{$this->auction->title}\" for \${$this->winningAmount}.",
            'link' => route('payment.checkout', $this->auction->id),
        ];
    }
}
