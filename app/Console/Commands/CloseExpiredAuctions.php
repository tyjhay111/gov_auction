<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Illuminate\Support\Facades\Log;

class CloseExpiredAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auctions:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close all active auctions that have passed their end time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredAuctions = Auction::where('status', 'active')
            ->where('end_time', '<=', now())
            ->get();

        $count = 0;

        foreach ($expiredAuctions as $auction) {
            $auction->update(['status' => 'closed']);
            $count++;
            
            // Optionally, we could dispatch an event here like AuctionClosed
            // to send notifications to the winner.
        }

        if ($count > 0) {
            $this->info("Successfully closed {$count} expired auctions.");
            Log::info("Closed {$count} expired auctions.");
        } else {
            $this->info("No expired active auctions found.");
        }
    }
}
