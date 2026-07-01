<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/auctions', \App\Livewire\AuctionList::class)->name('auctions.index');
Route::get('/auctions/{auction}', \App\Livewire\AuctionDetails::class)->name('auctions.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('watchlist', \App\Livewire\WatchlistManager::class)->name('watchlist');
    Route::get('my-bids', \App\Livewire\MyBids::class)->name('my-bids');
    Route::get('payment/{auction}', \App\Livewire\PaymentCheckout::class)->name('payment.checkout');

    Route::get('receipt/{payment}', function (\App\Models\Payment $payment) {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }
        return view('receipt', compact('payment'));
    })->name('receipt.show');
});

Route::middleware(['auth', 'verified', 'role:officer'])->group(function () {
    Route::get('/officer/auctions', \App\Livewire\OfficerAuctionManager::class)->name('officer.auctions');
    Route::get('/officer/analytics', \App\Livewire\OfficerAnalytics::class)->name('officer.analytics');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
});

require __DIR__.'/settings.php';
