<?php

namespace App\Livewire;

use App\Models\Auction;
use App\Models\Bid;
use App\Notifications\OutbidNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuctionDetails extends Component
{
    use WithFileUploads;

    public $auction;

    public $bidAmount;

    // Edit mode
    public $isEditing = false;

    public $editTitle;

    public $editDescription;

    public $editStartingPrice;

    public $editReservePrice;

    public $editStartTime;

    public $editEndTime;

    public $newImages = [];

    public function mount(Auction $auction)
    {
        $this->auction = $auction->load(['images', 'bids.user']);
        $this->bidAmount = $auction->current_price > 0 ? $auction->current_price + 1 : $auction->starting_price;
    }

    public function canManage(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'officer']);
    }

    public function placeBid()
    {
        $executed = RateLimiter::attempt(
            'place-bid:'.Auth::id(),
            $perMinute = 5,
            function () {
                // Rate limiter allowed
            }
        );

        if (! $executed) {
            session()->flash('error', 'Too many bids placed. Please wait a minute.');

            return;
        }

        if (! Auth::check()) {
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
            'bidAmount' => 'required|numeric|gt:'.$minBid,
        ]);

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

        if ($previousBidder && $previousBidder->id !== Auth::id()) {
            $previousBidder->notify(new OutbidNotification(
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
        if (! Auth::check()) {
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

    public function startEditing()
    {
        if (! $this->canManage()) {
            abort(403);
        }

        $this->editTitle = $this->auction->title;
        $this->editDescription = $this->auction->description;
        $this->editStartingPrice = $this->auction->starting_price;
        $this->editReservePrice = $this->auction->reserve_price;
        $this->editStartTime = optional($this->auction->start_time)->format('Y-m-d\TH:i');
        $this->editEndTime = optional($this->auction->end_time)->format('Y-m-d\TH:i');
        $this->newImages = [];
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->newImages = [];
    }

    public function updateAuction()
    {
        if (! $this->canManage()) {
            abort(403);
        }

        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'required|string',
            'editStartingPrice' => 'required|numeric|min:0',
            'editReservePrice' => 'nullable|numeric|min:0',
            'editStartTime' => 'required|date',
            'editEndTime' => 'required|date|after:editStartTime',
            'newImages.*' => 'image|max:2048',
        ]);

        $this->auction->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'starting_price' => $this->editStartingPrice,
            'reserve_price' => $this->editReservePrice,
            'start_time' => $this->editStartTime,
            'end_time' => $this->editEndTime,
        ]);

        foreach ($this->newImages as $image) {
            $path = $image->store('auctions', 'public');
            $this->auction->images()->create(['image_path' => $path]);
        }

        $this->auction->refresh();
        $this->auction->load('images');

        $this->isEditing = false;
        $this->newImages = [];

        session()->flash('message', 'Auction updated successfully.');
    }

    public function removeImage($imageId)
    {
        if (! $this->canManage()) {
            abort(403);
        }

        $image = $this->auction->images()->where('id', $imageId)->first();

        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->auction->refresh();
            $this->auction->load('images');
        }
    }

    public function render()
    {
        $isInWatchlist = Auth::check() ? Auth::user()->watchlists()->where('auction_id', $this->auction->id)->exists() : false;

        return view('livewire.auction-details', [
            'isInWatchlist' => $isInWatchlist,
            'canManage' => $this->canManage(),
        ])->layout('layouts.app');
    }
}
