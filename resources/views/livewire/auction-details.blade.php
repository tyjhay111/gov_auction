<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('auctions.index') }}" class="text-blue-600 hover:underline">&larr; Back to Auctions</a>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" class="mb-6">{{ session('message') }}</flux:toast>
    @endif

    @if (session()->has('error'))
        <flux:toast variant="danger" class="mb-6">{{ session('error') }}</flux:toast>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images -->
        <div class="space-y-4">
            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                @if($auction->images->count() > 0)
                    <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" alt="{{ $auction->title }}" />
                @else
                    <div class="flex items-center justify-center w-full h-full text-gray-400">
                        No Images Available
                    </div>
                @endif
            </div>

            @if($auction->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($auction->images->skip(1) as $image)
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ Storage::url($image->image_path) }}" class="object-cover w-full h-full" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Details -->
        <div class="flex flex-col gap-6">
            <div>
                <div class="flex justify-between items-start">
                    <div>
                        <flux:heading size="2xl" level="1">{{ $auction->title }}</flux:heading>
                        <div class="flex items-center gap-4 mt-2">
                            <flux:badge variant="{{ $auction->status === 'active' ? 'success' : 'danger' }}" size="lg">
                                {{ ucfirst($auction->status) }}
                            </flux:badge>
                            @auth
                                <button wire:click="toggleWatchlist" class="text-sm font-medium {{ $isInWatchlist ? 'text-red-500' : 'text-gray-500' }} hover:text-red-600 flex items-center gap-1 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $isInWatchlist ? 'fill-current' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $isInWatchlist ? 'Saved to Watchlist' : 'Add to Watchlist' }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="text-gray-500 mt-2 text-sm">Ends: {{ $auction->end_time->format('F j, Y H:i A') }}</div>

                @if($auction->status === 'active' && $auction->end_time > now())
                    <div class="mt-3 inline-flex items-center px-3 py-1 bg-orange-50 border border-orange-200 rounded-lg" x-data="countdown('{{ $auction->end_time->toIso8601String() }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <span class="text-sm font-semibold text-orange-600" x-text="timeRemaining"></span>
                    </div>
                @endif
            </div>

            <flux:card wire:poll.5s class="border-2 border-gradient-to-r from-blue-200 to-purple-200 bg-gradient-to-br from-white via-blue-50 to-white">
                <div class="flex justify-between items-center mb-8 pb-6 border-b-2 border-gray-100">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-2">Current Highest Bid</div>
                        <div class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-2">Starting Price</div>
                        <div class="text-2xl font-bold text-gray-700">${{ number_format($auction->starting_price, 2) }}</div>
                    </div>
                </div>

                @if($auction->status === 'active' && $auction->end_time > now())
                    @auth
                        @if(auth()->user()->role === 'bidder')
                            <form wire:submit.prevent="placeBid" class="space-y-4">
                                <div>
                                    <flux:input wire:model="bidAmount" type="number" step="0.01" label="Your Bid ($)" placeholder="Enter amount greater than current bid" required />
                                    <p class="text-xs text-gray-500 mt-2">Minimum bid: ${{ number_format(max($auction->current_price, $auction->starting_price) + 1, 2) }}</p>
                                </div>
                                <flux:button type="submit" variant="primary" class="w-full" size="lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13.5 15H11v1.5h2.5V15zM13.5 11H11v1.5h2.5V11zM13.5 7H11v1.5h2.5V7zM8 15H5.5v1.5H8V15zm0-4H5.5v1.5H8V11zm0-4H5.5v1.5H8V7zm5-2H6a2 2 0 00-2 2v12a2 2 0 002 2h7a2 2 0 002-2V7a2 2 0 00-2-2zm0 12H6V7h7v10z"/>
                                    </svg>
                                    Place Bid Now
                                </flux:button>
                            </form>
                        @else
                            <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl text-sm text-center border-2 border-yellow-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m6-12v2m0 4v2m0 4v2M6 9v2m0 4v2m0 4v2" />
                                </svg>
                                <p class="font-semibold text-yellow-800">You are logged in as an <strong>{{ auth()->user()->role }}</strong></p>
                                <p class="text-yellow-700 mt-1">Bidding is restricted to bidder accounts only.</p>
                            </div>
                        @endif
                    @else
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl text-center border-2 border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <p class="font-semibold text-blue-900 mb-3">Sign in to Place a Bid</p>
                            <flux:button href="{{ route('login') }}" variant="primary" class="w-full">
                                Login to Bid
                            </flux:button>
                        </div>
                    @endauth
                @else
                    <div class="p-6 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl text-center border-2 border-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto mb-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m0 4v2M6 9v2m0 4v2m0 4v2m12-18v2m0 4v2m0 4v2" />
                        </svg>
                        <p class="font-semibold text-red-900">Auction Ended</p>
                        <p class="text-red-700 text-sm mt-1">This auction is no longer accepting bids.</p>
                    </div>
                @endif
            </flux:card>

            <div>
                <flux:heading size="lg" class="mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Description
                </flux:heading>
                <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    {!! nl2br(e($auction->description)) !!}
                </div>
            </div>

            <div>
                <flux:heading size="lg" class="mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Bid History ({{ $auction->bids->count() }})
                </flux:heading>
                <flux:card class="p-0 overflow-hidden border-2 border-gray-200" wire:poll.5s>
                    @if($auction->bids->count() > 0)
                        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                            @foreach($auction->bids->sortByDesc('amount') as $bid)
                                <div class="p-4 flex justify-between items-center text-sm hover:bg-blue-50 transition duration-200 {{ $loop->first ? 'bg-gradient-to-r from-green-50 to-transparent border-l-4 border-l-green-500' : '' }}">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $bid->user->name }}
                                            @if(auth()->id() === $bid->user_id)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2 font-medium">You</span>
                                            @endif
                                            @if($loop->first)
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2 font-medium">🏆 Highest</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $bid->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">${{ number_format($bid->amount, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4a1 1 0 011-1h16a1 1 0 011 1v2.757l-5.753 5.753M3 4v14a2 2 0 002 2h14a2 2 0 002-2V4" />
                            </svg>
                            <p class="text-gray-500 font-medium">No bids yet</p>
                            <p class="text-gray-400 text-sm">Be the first to place a bid!</p>
                        </div>
                    @endif
                </flux:card>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (endTime) => ({
                timeRemaining: '',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const now = new Date().getTime();
                    const end = new Date(endTime).getTime();
                    const distance = end - now;

                    if (distance < 0) {
                        this.timeRemaining = 'Ended';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    if (days > 0) {
                        this.timeRemaining = `${days}d ${hours}h left`;
                    } else if (hours > 0) {
                        this.timeRemaining = `${hours}h ${minutes}m left`;
                    } else if (minutes > 0) {
                        this.timeRemaining = `${minutes}m ${seconds}s left`;
                    } else {
                        this.timeRemaining = `${seconds}s left`;
                    }
                }
            }));
        });
    </script>
</div>
