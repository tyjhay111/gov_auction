<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">Your Watchlist</h1>
        <p class="text-zinc-600 dark:text-zinc-400">Keep track of auctions you care about and revisit them quickly.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    @if($watchlists->isEmpty())
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-12 text-center shadow-sm">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Your watchlist is empty.</h2>
            <p class="mb-6 text-sm text-zinc-600 dark:text-zinc-400">
                Save auctions you like and come back to them any time.
            </p>
            <a href="{{ route('auctions.index') }}" class="inline-block rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Browse Auctions
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($watchlists as $watchlist)
                @php $auction = $watchlist->auction; @endphp
                <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($auction->images->count() > 0)
                            <img src="{{ Storage::url($auction->images->first()->image_path) }}" alt="{{ $auction->title }}" class="h-56 w-full object-cover transition duration-300" />
                        @else
                            <div class="flex h-56 items-center justify-center text-zinc-400 dark:text-zinc-500">
                                No image available
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4 p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-1">{{ $auction->title }}</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Ends {{ $auction->end_time->format('M d, Y H:i') }}</p>
                            </div>

                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $auction->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </div>

                        <div class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                            <p>Current bid:</p>
                            <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('auctions.show', $auction->id) }}" class="flex-1 text-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                View Auction
                            </a>
                            <button wire:click="remove({{ $auction->id }})" class="flex-1 rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
