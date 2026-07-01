<?php

use App\Livewire\AdminDashboard;
use App\Livewire\AuctionDetails;
use App\Livewire\AuctionList;
use App\Livewire\MyBids;
use App\Livewire\OfficerAnalytics;
use App\Livewire\OfficerAuctionManager;
use App\Livewire\PaymentCheckout;
use App\Livewire\WatchlistManager;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/auctions', AuctionList::class)->name('auctions.index');
Route::get('/auctions/{auction}', AuctionDetails::class)->name('auctions.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
    Route::get('/admin/bids', \App\Livewire\Admin\BidMonitoring::class)->name('admin.bids');

    Route::get('watchlist', WatchlistManager::class)->name('watchlist');
    Route::get('my-bids', MyBids::class)->name('my-bids');
    Route::get('payment/{auction}', PaymentCheckout::class)->name('payment.checkout');

    Route::get('receipt/{payment}', function (Payment $payment) {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('receipt', compact('payment'));
    })->name('receipt.show');
});

Route::middleware(['auth', 'verified', 'role:officer'])->group(function () {
    Route::get('/officer/auctions', OfficerAuctionManager::class)->name('officer.auctions');
    Route::get('/officer/analytics', OfficerAnalytics::class)->name('officer.analytics');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
});

require __DIR__.'/settings.php';
