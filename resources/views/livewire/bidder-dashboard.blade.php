<div class="container mx-auto px-2 py-4 sm:px-4 sm:py-8">
    <div class="mb-8">
        <flux:heading size="xl" level="1" class="mb-2">Bidder Dashboard</flux:heading>
        <flux:subheading size="lg">Welcome back, {{ auth()->user()->name }}. Keep track of your bidding activity.</flux:subheading>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total Bids', 'value' => number_format($stats['total_bids']), 'color' => 'blue'],
            ['label' => 'Active Bids', 'value' => number_format($stats['active_bids']), 'color' => 'amber'],
            ['label' => 'Items Won', 'value' => number_format($stats['items_won']), 'color' => 'green'],
            ['label' => 'Total Spent', 'value' => '$'.number_format($stats['total_spent'], 2), 'color' => 'violet'],
        ] as $stat)
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mb-6 flex flex-wrap gap-2 border-b border-zinc-200 dark:border-zinc-700">
        @foreach([
            'active' => 'Active Bids',
            'watchlist' => 'Watchlist',
            'won' => 'Won Auctions',
            'recent' => 'Recent Bids',
        ] as $tab => $label)
            <button wire:click="$set('activeTab', '{{ $tab }}')" class="border-b-2 px-4 py-2 text-sm font-medium transition {{ $activeTab === $tab ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if($activeTab === 'active')
        <div class="space-y-4">
            @forelse($activeBids as $auction)
                @php
                    $yourBid = $auction->bids->where('user_id', auth()->id())->sortByDesc('amount')->first();
                    $highestBid = $auction->bids->first();
                @endphp
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $auction->title }}</h2>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Ends {{ $auction->end_time->diffForHumans() }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <span class="text-zinc-500 dark:text-zinc-400">Your bid <strong class="text-zinc-900 dark:text-zinc-100">${{ number_format($yourBid?->amount ?? 0, 2) }}</strong></span>
                            <span class="{{ $highestBid?->user_id === auth()->id() ? 'text-green-600' : 'text-red-600' }}">{{ $highestBid?->user_id === auth()->id() ? 'Highest bidder' : 'Outbid' }}</span>
                            <a href="{{ route('auctions.show', $auction) }}" class="font-medium text-blue-600 hover:text-blue-700">View auction</a>
                        </div>
                    </div>
                </div>
            @empty
                <x-bidder-dashboard-empty message="You have no active bids." action="Browse Auctions" :href="route('auctions.index')" />
            @endforelse
            {{ $activeBids->links() }}
        </div>
    @elseif($activeTab === 'watchlist')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($watchlist as $item)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <h2 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $item->auction->title }}</h2>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Current bid: ${{ number_format($item->auction->current_price ?: $item->auction->starting_price, 2) }}</p>
                    <a href="{{ route('auctions.show', $item->auction) }}" class="mt-4 inline-block text-sm font-medium text-blue-600 hover:text-blue-700">View auction</a>
                </div>
            @empty
                <div class="md:col-span-2 xl:col-span-3"><x-bidder-dashboard-empty message="Your watchlist is empty." action="Browse Auctions" :href="route('auctions.index')" /></div>
            @endforelse
        </div>
        <div class="mt-6">{{ $watchlist->links() }}</div>
    @elseif($activeTab === 'won')
        <div class="space-y-4">
            @forelse($wonAuctions as $auction)
                @php $winningBid = $auction->bids->where('user_id', auth()->id())->sortByDesc('amount')->first(); @endphp
                <div class="rounded-xl border border-green-200 bg-green-50 p-5 dark:border-green-900 dark:bg-green-950/30">
                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $auction->title }}</h2>
                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Won for ${{ number_format($winningBid->amount, 2) }}</p>
                        </div>
                        <a href="{{ route('auctions.show', $auction) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View auction</a>
                    </div>
                </div>
            @empty
                <x-bidder-dashboard-empty message="You have not won any auctions yet." action="Browse Auctions" :href="route('auctions.index')" />
            @endforelse
            {{ $wonAuctions->links() }}
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr><th class="px-5 py-3 text-left text-xs font-medium uppercase text-zinc-500">Auction</th><th class="px-5 py-3 text-left text-xs font-medium uppercase text-zinc-500">Bid amount</th><th class="px-5 py-3 text-left text-xs font-medium uppercase text-zinc-500">Placed</th></tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($recentBids as $bid)
                            <tr><td class="px-5 py-4 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $bid->auction->title }}</td><td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">${{ number_format($bid->amount, 2) }}</td><td class="px-5 py-4 text-sm text-zinc-600 dark:text-zinc-400">{{ $bid->created_at->diffForHumans() }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-sm text-zinc-500">You have not placed any bids yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-700">{{ $recentBids->links() }}</div>
        </div>
    @endif
</div>
