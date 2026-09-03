<?php

namespace App\Livewire;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Payment;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BidderDashboard extends Component
{
    use WithPagination;

    public string $activeTab = 'active';

    public function render()
    {
        $userId = Auth::id();

        $activeBids = Auction::query()
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->whereHas('bids', fn ($query) => $query->where('user_id', $userId))
            ->with(['images', 'bids' => fn ($query) => $query->latest('amount')])
            ->latest('end_time')
            ->paginate(6, ['*'], 'activeBidsPage');

        $wonAuctions = Auction::query()
            ->where(function ($query) {
                $query->where('status', 'closed')->orWhere('end_time', '<=', now());
            })
            ->whereRaw(
                'EXISTS (
                    SELECT 1
                    FROM bids winning_bid
                    WHERE winning_bid.auction_id = auctions.id
                      AND winning_bid.user_id = ?
                      AND winning_bid.amount = (
                          SELECT MAX(all_bids.amount)
                          FROM bids all_bids
                          WHERE all_bids.auction_id = auctions.id
                      )
                )',
                [$userId]
            )
            ->with(['images', 'bids' => fn ($query) => $query->latest('amount')])
            ->latest('end_time')
            ->paginate(6, ['*'], 'wonAuctionsPage');

        $watchlist = Watchlist::query()
            ->where('user_id', $userId)
            ->with(['auction.images', 'auction.bids'])
            ->latest()
            ->paginate(6, ['*'], 'watchlistPage');

        $recentBids = Bid::query()
            ->where('user_id', $userId)
            ->with('auction')
            ->latest()
            ->paginate(10, ['*'], 'recentBidsPage');

        return view('livewire.bidder-dashboard', [
            'stats' => [
                'total_bids' => Bid::where('user_id', $userId)->count(),
                'active_bids' => Auction::where('status', 'active')
                    ->where('end_time', '>', now())
                    ->whereHas('bids', fn ($query) => $query->where('user_id', $userId))
                    ->count(),
                'items_won' => $wonAuctions->total(),
                'total_spent' => Payment::where('user_id', $userId)
                    ->where('status', 'completed')
                    ->sum('amount'),
            ],
            'activeBids' => $activeBids,
            'wonAuctions' => $wonAuctions,
            'watchlist' => $watchlist,
            'recentBids' => $recentBids,
        ]);
    }
}
