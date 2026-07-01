<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <flux:heading size="xl" level="1" class="mb-2">My Bidding Activity</flux:heading>
        <flux:subheading size="lg" class="text-gray-600">Track your active bids, won auctions, and bidding history.</flux:subheading>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8 flex flex-wrap gap-2 border-b border-gray-200 pb-4">
        <a href="#active" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition bg-blue-100 text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Active Bids ({{ count($activeBids) }})
        </a>
        <a href="#won" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
            </svg>
            Won ({{ count($wonAuctions) }})
        </a>
        <a href="#history" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
            </svg>
            History ({{ count($lostAuctions) }})
        </a>
    </div>

    <!-- Active Bids -->
    <div id="active" class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Active Bids
        </h2>
        <div class="space-y-4">
            @forelse($activeBids as $data)
                @php $auction = $data['auction']; @endphp
                <flux:card class="overflow-hidden border-l-4 {{ $data['is_highest'] ? 'border-l-green-500' : 'border-l-red-500' }} hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($auction->images->count() > 0)
                                <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" alt="{{ $auction->title }}" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $auction->title }}</h2>
                                <div class="mt-2 flex flex-wrap gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-600">Current Price:</span>
                                        <span class="ml-1 font-bold text-gray-900">${{ number_format($auction->current_price, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Your Bid:</span>
                                        <span class="ml-1 font-bold text-blue-600">${{ number_format($data['user_bid'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                @if($data['is_highest'])
                                    <flux:badge variant="success" size="lg" class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                                        </svg>
                                        You are the highest bidder
                                    </flux:badge>
                                @else
                                    <flux:badge variant="danger" size="lg" class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                        </svg>
                                        You have been outbid
                                    </flux:badge>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end justify-between">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Ends</div>
                                <div class="font-semibold text-gray-900">{{ $auction->end_time->diffForHumans() }}</div>
                            </div>
                            <flux:button href="{{ route('auctions.show', $auction->id) }}" variant="primary" size="sm">
                                View Auction
                            </flux:button>
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 font-medium">You have no active bids.</p>
                    <p class="text-sm text-gray-500 mt-1">Browse auctions and place a bid to get started.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Won Auctions -->
    <div id="won" class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
            </svg>
            Won Auctions
        </h2>
        <div class="space-y-4">
            @forelse($wonAuctions as $data)
                @php
                    $auction = $data['auction'];
                    $payment = \App\Models\Payment::where('auction_id', $auction->id)->where('user_id', auth()->id())->first();
                @endphp
                <flux:card class="overflow-hidden border-l-4 border-l-green-500 bg-gradient-to-r from-green-50 to-white hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0 ring-2 ring-green-300">
                            @if($auction->images->count() > 0)
                                <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" alt="{{ $auction->title }}" />
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $auction->title }}</h2>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                </div>
                                <div class="flex flex-wrap gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-600">Winning Bid:</span>
                                        <span class="ml-1 font-bold text-gray-900">${{ number_format($data['user_bid'], 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Ended:</span>
                                        <span class="ml-1 font-semibold">{{ $auction->end_time->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end justify-between">
                            @if($payment && $payment->status === 'completed')
                                <div class="text-right">
                                    <flux:badge variant="success" class="inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                                        </svg>
                                        Paid
                                    </flux:badge>
                                    <p class="text-xs text-gray-600 mt-2">Ref: {{ $payment->reference }}</p>
                                </div>
                                <flux:button href="{{ route('receipt.show', $payment->id) }}" variant="secondary" size="sm" target="_blank">
                                    View Receipt
                                </flux:button>
                            @else
                                <div class="text-right">
                                    <flux:badge variant="warning" class="inline-block mb-2">
                                        Awaiting Payment
                                    </flux:badge>
                                </div>
                                <flux:button href="{{ route('payment.checkout', $auction->id) }}" variant="primary" size="sm">
                                    Pay Now
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 20H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-5l4 4v-4h2" />
                    </svg>
                    <p class="text-gray-600 font-medium">You haven't won any auctions yet.</p>
                    <p class="text-sm text-gray-500 mt-1">Keep bidding on auctions to secure a win!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Auction History -->
    <div id="history">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
            </svg>
            Bidding History
        </h2>
        <div class="space-y-2">
            @forelse($lostAuctions as $data)
                @php $auction = $data['auction']; @endphp
                <flux:card class="opacity-75 hover:opacity-100 transition">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                            @if($auction->images->count() > 0)
                                <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" alt="{{ $auction->title }}" />
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $auction->title }}</h3>
                            <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-600">
                                <span>Final Price: <strong class="text-gray-900">${{ number_format($auction->current_price, 2) }}</strong></span>
                                <span class="text-gray-400">•</span>
                                <span>Your Bid: <strong class="text-gray-900">${{ number_format($data['user_bid'], 2) }}</strong></span>
                            </div>
                        </div>
                        <flux:badge variant="secondary">Closed</flux:badge>
                    </div>
                </flux:card>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                    <p class="text-gray-600 font-medium">No bidding history yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
