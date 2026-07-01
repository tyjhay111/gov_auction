<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WatchlistManager extends Component
{
    public function remove($auctionId)
    {
        Auth::user()->watchlists()->where('auction_id', $auctionId)->delete();
        session()->flash('message', 'Auction removed from your watchlist.');
    }

    public function render()
    {
        $watchlists = Auth::user()
            ->watchlists()
            ->with(['auction.images', 'auction.bids'])
            ->latest()
            ->get();

        return view('livewire.watchlist-manager', [
            'watchlists' => $watchlists,
        ])->layout('layouts.app');
    }
}
