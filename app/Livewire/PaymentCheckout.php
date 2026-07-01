<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Auction;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentCheckout extends Component
{
    public $auction;
    public $amount;

    public $cardNumber = '';
    public $expiry = '';
    public $cvc = '';
    public $nameOnCard = '';

    public function mount(Auction $auction)
    {
        $this->auction = $auction;

        // Verify the user actually won this auction
        $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();

        if (!$highestBid || $highestBid->user_id !== Auth::id()) {
            abort(403, 'You are not the winner of this auction.');
        }

        if ($auction->status !== 'closed' && $auction->end_time > now()) {
            abort(403, 'This auction has not ended yet.');
        }

        $this->amount = $highestBid->amount;

        // Check if already paid
        $existingPayment = Payment::where('auction_id', $auction->id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            session()->flash('message', 'This auction has already been paid for.');
            $this->redirectRoute('my-bids');
        }
    }

    public function processPayment()
    {
        $this->validate([
            'cardNumber' => 'required|string|min:16',
            'expiry' => 'required|string|min:5',
            'cvc' => 'required|string|min:3',
            'nameOnCard' => 'required|string'
        ]);

        // Mock payment processing logic
        sleep(1); 

        // Record the payment
        Payment::create([
            'user_id' => Auth::id(),
            'auction_id' => $this->auction->id,
            'amount' => $this->amount,
            'status' => 'completed',
            'reference' => 'TXN-' . strtoupper(Str::random(10)),
        ]);

        session()->flash('message', 'Payment successful! Receipt has been generated.');
        return redirect()->route('my-bids');
    }

    public function render()
    {
        return view('livewire.payment-checkout')->layout('layouts.app');
    }
}
