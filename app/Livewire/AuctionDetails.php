<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuctionDetails extends Component
{
    public $auction;
    public $bidAmount;

    public function mount(Auction $auction)
    {
        $this->auction = $auction->load(['images', 'bids.user']);
        $this->bidAmount = $auction->current_price > 0 ? $auction->current_price + 1 : $auction->starting_price;
    }

    public function placeBid()
    {
        $executed = RateLimiter::attempt(
            'place-bid:' . Auth::id(),
            $perMinute = 5,
            function() {
                // Rate limiter allowed
            }
        );

        if (! $executed) {
            session()->flash('error', 'Too many bids placed. Please wait a minute.');
            return;
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'officer' || Auth::user()->role === 'admin') {
            session()->flash('error', 'Officers and admins cannot place bids.');
            return;
        }

        if ($this->auction->status !== 'active') {
            session()->flash('error', 'This auction is not currently active.');
            return;
        }

        if ($this->auction->end_time < now()) {
            session()->flash('error', 'This auction has ended.');
            return;
        }

        $highestBid = $this->auction->bids()->orderBy('amount', 'desc')->first();
        if ($highestBid && $highestBid->user_id === Auth::id()) {
            session()->flash('error', 'You are already the highest bidder.');
            return;
        }

        $minBid = max($this->auction->current_price, $this->auction->starting_price);

        $this->validate([
            'bidAmount' => 'required|numeric|gt:' . $minBid,
        ]);

        // Get the previous highest bidder before creating new bid
        $previousHighestBid = $this->auction->bids()->orderBy('amount', 'desc')->first();
        $previousBidder = $previousHighestBid?->user;

        Bid::create([
            'auction_id' => $this->auction->id,
            'user_id' => Auth::id(),
            'amount' => $this->bidAmount,
        ]);

        $this->auction->update([
            'current_price' => $this->bidAmount,
        ]);

        // Send outbid notification to previous highest bidder
        if ($previousBidder && $previousBidder->id !== Auth::id()) {
            $previousBidder->notify(new \App\Notifications\OutbidNotification(
                $this->auction,
                $this->bidAmount,
                Auth::user()
            ));
        }

        $this->auction->refresh();
        $this->bidAmount = $this->auction->current_price + 1;

        session()->flash('message', 'Bid placed successfully!');
    }

    public function toggleWatchlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->watchlists()->where('auction_id', $this->auction->id)->exists()) {
            $user->watchlists()->where('auction_id', $this->auction->id)->delete();
            session()->flash('message', 'Removed from watchlist.');
        } else {
            $user->watchlists()->create(['auction_id' => $this->auction->id]);
            session()->flash('message', 'Added to watchlist.');
        }
    }

    public function render()
    {
        $isInWatchlist = Auth::check() ? Auth::user()->watchlists()->where('auction_id', $this->auction->id)->exists() : false;

        return view('livewire.auction-details', [
            'isInWatchlist' => $isInWatchlist
        ])->layout('layouts.app');
    }
}
