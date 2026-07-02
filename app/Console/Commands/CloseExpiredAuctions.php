<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Notifications\AuctionWonNotification;
use App\Notifications\AuctionClosedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloseExpiredAuctions extends Command
{
    protected $signature = 'auctions:close-expired';

    protected $description = 'Close all active auctions that have passed their end time';

    public function handle()
    {
        $expiredAuctions = Auction::where('status', 'active')
            ->where('end_time', '<=', now())
            ->with('bids.user', 'creator')
            ->get();

        $count = 0;

        foreach ($expiredAuctions as $auction) {
            $auction->update(['status' => 'closed']);
            $count++;

            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            if ($highestBid && $highestBid->user) {
                $highestBid->user->notify(new AuctionWonNotification($auction, $highestBid->amount));
            }

            if ($auction->creator) {
                $auction->creator->notify(new AuctionClosedNotification($auction, $highestBid));
            }
        }

        if ($count > 0) {
            $this->info("Successfully closed {$count} expired auctions.");
            Log::info("Closed {$count} expired auctions.");
        } else {
            $this->info('No expired active auctions found.');
        }
    }
}
