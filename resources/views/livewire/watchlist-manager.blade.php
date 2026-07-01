<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <flux:heading size="xl" level="1" class="mb-2">Your Watchlist</flux:heading>
        <flux:subheading size="lg" class="text-gray-600 dark:text-gray-300">Keep track of auctions you care about and revisit them quickly.</flux:subheading>
    </div>

    @if (session()->has('message'))
        <flux:toast variant="success" class="mb-6">{{ session('message') }}</flux:toast>
    @endif

    @if($watchlists->isEmpty())
        <div class="rounded-3xl border border-gray-200 bg-white p-12 text-center shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-zinc-800 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <flux:heading size="lg" class="mb-2">Your watchlist is empty.</flux:heading>
            <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                Save auctions you like and come back to them any time.
            </p>
            <flux:button href="{{ route('auctions.index') }}" variant="secondary">Browse Auctions</flux:button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($watchlists as $watchlist)
                @php $auction = $watchlist->auction; @endphp
                <flux:card class="overflow-hidden rounded-3xl border border-gray-200 bg-white p-0 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-950">
                    <div class="overflow-hidden bg-slate-100 dark:bg-zinc-900">
                        @if($auction->images->count() > 0)
                            <img src="{{ Storage::url($auction->images->first()->image_path) }}" alt="{{ $auction->title }}" class="h-56 w-full object-cover transition duration-300" />
                        @else
                            <div class="flex h-56 items-center justify-center text-gray-400 dark:text-gray-500">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4 p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <flux:heading size="md" class="mb-1">{{ $auction->title }}</flux:heading>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Ends {{ $auction->end_time->format('M d, Y H:i') }}</p>
                            </div>

                            <flux:badge variant="{{ $auction->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($auction->status) }}
                            </flux:badge>
                        </div>

                        <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <p>Current bid:</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <flux:button href="{{ route('auctions.show', $auction->id) }}" variant="primary" size="sm" class="flex-1">View Auction</flux:button>
                            <flux:button wire:click="remove({{ $auction->id }})" variant="danger" size="sm" class="flex-1">Remove</flux:button>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>
    @endif
</div>
