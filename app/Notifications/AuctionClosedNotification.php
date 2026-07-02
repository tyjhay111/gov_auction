<?php

namespace App\Notifications;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuctionClosedNotification extends Notification
{
    use Queueable;

    protected $auction;

    protected $highestBid;

    public function __construct(Auction $auction, ?Bid $highestBid)
    {
        $this->auction = $auction;
        $this->highestBid = $highestBid;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Your auction has closed: {$this->auction->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your auction **{$this->auction->title}** has closed.");

        if ($this->highestBid) {
            $mail->line('Winning bid: $'.number_format($this->highestBid->amount, 2))
                ->line("Winner: {$this->highestBid->user->name}");
        } else {
            $mail->line('No bids were placed on this auction.');
        }

        return $mail->action('View Auction', route('auctions.show', $this->auction->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
            'winner' => $this->highestBid?->user?->name,
            'amount' => $this->highestBid?->amount,
            'message' => $this->highestBid
                ? "Your auction \"{$this->auction->title}\" closed. Winner: {$this->highestBid->user->name}."
                : "Your auction \"{$this->auction->title}\" closed with no bids.",
            'link' => route('auctions.show', $this->auction->id),
        ];
    }
}
