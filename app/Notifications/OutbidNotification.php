<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Auction;
use App\Models\Bid;

class OutbidNotification extends Notification
{
    use Queueable;

    protected $auction;
    protected $newBidAmount;
    protected $newBidder;

    /**
     * Create a new notification instance.
     */
    public function __construct(Auction $auction, $newBidAmount, $newBidder)
    {
        $this->auction = $auction;
        $this->newBidAmount = $newBidAmount;
        $this->newBidder = $newBidder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You've been outbid on {$this->auction->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You've been outbid on the auction: **{$this->auction->title}**")
            ->line("New highest bid: \$" . number_format($this->newBidAmount, 2))
            ->line("Bidder: {$this->newBidder->name}")
            ->action('View Auction', route('auctions.show', $this->auction->id))
            ->line('You can place a new bid to stay in the running!')
            ->line('Thank you for participating in our auctions!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
