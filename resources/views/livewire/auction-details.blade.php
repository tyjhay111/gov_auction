<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('auctions.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">&larr; Back to Auctions</a>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/30 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if($canManage)
        <div class="mb-6 flex justify-end">
            @if(!$isEditing)
                <button wire:click="startEditing" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Edit Auction
                </button>
            @else
                <button wire:click="cancelEditing" class="rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    Cancel Editing
                </button>
            @endif
        </div>
    @endif

    @if($isEditing)
        <div class="mb-8 rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Edit Auction</h3>
            <form wire:submit.prevent="updateAuction" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Title</label>
                    <input wire:model="editTitle" type="text" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('editTitle') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Description</label>
                    <textarea wire:model="editDescription" rows="4" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('editDescription') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Starting Price ($)</label>
                        <input wire:model="editStartingPrice" type="number" step="0.01" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('editStartingPrice') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Reserve Price ($)</label>
                        <input wire:model="editReservePrice" type="number" step="0.01" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('editReservePrice') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Start Time</label>
                        <input wire:model="editStartTime" type="datetime-local" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('editStartTime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">End Time</label>
                        <input wire:model="editEndTime" type="datetime-local" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('editEndTime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Current Images</label>
                    <div class="flex flex-wrap gap-3">
                        @forelse($auction->images as $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image->image_path) }}" class="h-20 w-20 object-cover rounded-md border border-zinc-200 dark:border-zinc-700" />
                                <button type="button" wire:click="removeImage({{ $image->id }})" wire:confirm="Remove this image?" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">&times;</button>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">No images yet.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Add Images</label>
                    <input wire:model="newImages" type="file" multiple accept="image/*" class="w-full text-sm text-zinc-700 dark:text-zinc-300" />
                    @error('newImages.*') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="newImages" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Uploading...</div>
                </div>

                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Save Changes
                </button>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images -->
        <div class="space-y-4">
            <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700">
                @if($auction->images->count() > 0)
                    <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" alt="{{ $auction->title }}" />
                @else
                    <div class="flex items-center justify-center w-full h-full text-zinc-400 dark:text-zinc-500">
                        No Images Available
                    </div>
                @endif
            </div>

            @if($auction->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($auction->images->skip(1) as $image)
                        <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
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
                        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $auction->title }}</h1>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $auction->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                            @auth
                                <button wire:click="toggleWatchlist" class="text-sm font-medium {{ $isInWatchlist ? 'text-red-500' : 'text-zinc-500 dark:text-zinc-400' }} hover:text-red-600 flex items-center gap-1 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $isInWatchlist ? 'fill-current' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    {{ $isInWatchlist ? 'Saved to Watchlist' : 'Add to Watchlist' }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="text-zinc-500 dark:text-zinc-400 mt-2 text-sm">Ends: {{ $auction->end_time->format('F j, Y H:i A') }}</div>

                @if($auction->status === 'active' && $auction->end_time > now())
                    <div class="mt-3 inline-flex items-center px-3 py-1 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800 rounded-lg" x-data="countdown('{{ $auction->end_time->toIso8601String() }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600 dark:text-orange-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <span class="text-sm font-semibold text-orange-600 dark:text-orange-400" x-text="timeRemaining"></span>
                    </div>
                @endif
            </div>

            <div wire:poll.5s class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-2">Current Highest Bid</div>
                        <div class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400 mb-2">Starting Price</div>
                        <div class="text-xl font-semibold text-zinc-700 dark:text-zinc-300">${{ number_format($auction->starting_price, 2) }}</div>
                    </div>
                </div>

                @if($auction->status === 'active' && $auction->end_time > now())
                    @auth
                        @if(auth()->user()->role === 'bidder')
                            <form wire:submit.prevent="placeBid" class="space-y-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Your Bid ($)</label>
                                    <input wire:model="bidAmount" type="number" step="0.01" placeholder="Enter amount greater than current bid" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Minimum bid: ${{ number_format(max($auction->current_price, $auction->starting_price) + 1, 2) }}</p>
                                    @error('bidAmount') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <button type="submit" class="w-full rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700">
                                    Place Bid Now
                                </button>
                            </form>
                        @else
                            <div class="p-6 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl text-sm text-center border border-yellow-200 dark:border-yellow-800">
                                <p class="font-semibold text-yellow-800 dark:text-yellow-400">You are logged in as an <strong>{{ auth()->user()->role }}</strong></p>
                                <p class="text-yellow-700 dark:text-yellow-500 mt-1">Bidding is restricted to bidder accounts only.</p>
                            </div>
                        @endif
                    @else
                        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-center border border-blue-200 dark:border-blue-800">
                            <p class="font-semibold text-blue-900 dark:text-blue-400 mb-3">Sign in to Place a Bid</p>
                            <a href="{{ route('login') }}" class="block w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                Login to Bid
                            </a>
                        </div>
                    @endauth
                @else
                    <div class="p-6 bg-red-50 dark:bg-red-900/20 rounded-xl text-center border border-red-200 dark:border-red-800">
                        <p class="font-semibold text-red-900 dark:text-red-400">Auction Ended</p>
                        <p class="text-red-700 dark:text-red-500 text-sm mt-1">This auction is no longer accepting bids.</p>
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Description</h3>
                <div class="prose dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 p-4 rounded-lg border border-zinc-200 dark:border-zinc-700">
                    {!! nl2br(e($auction->description)) !!}
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Bid History ({{ $auction->bids->count() }})</h3>
                <div wire:poll.5s class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    @if($auction->bids->count() > 0)
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800 max-h-80 overflow-y-auto">
                            @foreach($auction->bids->sortByDesc('amount') as $bid)
                                <div class="p-4 flex justify-between items-center text-sm {{ $loop->first ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-l-green-500' : '' }}">
                                    <div>
                                        <p class="font-semibold text-zinc-900 dark:text-zinc-100">
                                            {{ $bid->user->name }}
                                            @if(auth()->id() === $bid->user_id)
                                                <span class="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-400 px-2 py-1 rounded-full ml-2 font-medium">You</span>
                                            @endif
                                            @if($loop->first)
                                                <span class="text-xs bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 px-2 py-1 rounded-full ml-2 font-medium">Highest</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $bid->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-lg font-bold text-zinc-900 dark:text-zinc-100">${{ number_format($bid->amount, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <p class="text-zinc-500 dark:text-zinc-400 font-medium">No bids yet</p>
                            <p class="text-zinc-400 dark:text-zinc-500 text-sm">Be the first to place a bid!</p>
                        </div>
                    @endif
                </div>
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
