<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class MyBids extends Component
{
    public function render()
    {
        $userId = Auth::id();

        // Get IDs of all auctions the user has bid on
        $biddedAuctionIds = Bid::where('user_id', $userId)->pluck('auction_id')->unique();

        // Load those auctions
        $auctions = Auction::whereIn('id', $biddedAuctionIds)
            ->with(['images', 'bids' => function ($query) {
                $query->orderBy('amount', 'desc');
            }])
            ->get();

        $activeBids = [];
        $wonAuctions = [];
        $lostAuctions = [];

        foreach ($auctions as $auction) {
            $highestBid = $auction->bids->first();
            $userHighestBid = $auction->bids->where('user_id', $userId)->first();
            
            $auctionData = [
                'auction' => $auction,
                'user_bid' => $userHighestBid->amount,
                'is_highest' => $highestBid->user_id === $userId,
            ];

            if ($auction->status === 'closed' || $auction->end_time <= now()) {
                if ($auctionData['is_highest']) {
                    $wonAuctions[] = $auctionData;
                } else {
                    $lostAuctions[] = $auctionData;
                }
            } else {
                $activeBids[] = $auctionData;
            }
        }

        return view('livewire.my-bids', [
            'activeBids' => $activeBids,
            'wonAuctions' => $wonAuctions,
            'lostAuctions' => $lostAuctions,
        ])->layout('layouts.app');
    }
}
