<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">Auction Analytics</h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-8">Detailed insights into your auction performance.</p>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-5 shadow-sm border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Auctions</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $totalAuctions }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex gap-2 text-sm flex-wrap">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">Active: {{ $activeAuctions }}</span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">Closed: {{ $closedAuctions }}</span>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-5 shadow-sm border-l-4 border-l-green-500">
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Revenue</p>
            <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">${{ number_format($totalRevenue, 2) }}</p>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-5 shadow-sm border-l-4 border-l-purple-500">
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Bids</p>
            <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $totalBids }}</p>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-5 shadow-sm border-l-4 border-l-orange-500">
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Avg. Bids/Auction</p>
            <p class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mt-2">{{ $averageBidsPerAuction }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Performing Auctions -->
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Top Performing Auctions</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                <th class="text-left py-3 px-4 font-semibold text-zinc-700 dark:text-zinc-300">Title</th>
                                <th class="text-right py-3 px-4 font-semibold text-zinc-700 dark:text-zinc-300">Final Price</th>
                                <th class="text-right py-3 px-4 font-semibold text-zinc-700 dark:text-zinc-300">Bids</th>
                                <th class="text-right py-3 px-4 font-semibold text-zinc-700 dark:text-zinc-300">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAuctions as $auction)
                                @php
                                    $badgeClass = match($auction->status) {
                                        'active' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400',
                                        'draft' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400',
                                        'closed' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
                                        'suspended' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400',
                                        default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                                    };
                                @endphp
                                <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                    <td class="py-3 px-4">
                                        <a href="{{ route('auctions.show', $auction->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium truncate block">
                                            {{ $auction->title }}
                                        </a>
                                    </td>
                                    <td class="text-right py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($auction->current_price, 2) }}</td>
                                    <td class="text-right py-3 px-4 text-zinc-600 dark:text-zinc-400">{{ $auction->bids->count() }}</td>
                                    <td class="text-right py-3 px-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeClass }}">
                                            {{ ucfirst($auction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No auctions yet. Create your first auction to see analytics.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div>
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Recent Bids</h2>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentBids as $bid)
                        <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                            <div class="flex justify-between items-start mb-1">
                                <p class="font-semibold text-sm text-zinc-900 dark:text-zinc-100 truncate">{{ $bid->user->name }}</p>
                                <p class="font-bold text-sm text-green-600 dark:text-green-400">${{ number_format($bid->amount, 2) }}</p>
                            </div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 truncate">{{ $bid->auction->title }}</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">{{ $bid->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-center text-zinc-500 dark:text-zinc-400 py-6">No bids yet on your auctions.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bid Activity Chart -->
    <div class="mt-6">
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">7-Day Bid Activity</h2>
            <div class="h-64 flex items-end gap-2 p-4 rounded-lg bg-zinc-50 dark:bg-zinc-800">
                @if(count($chartCounts) > 0)
                    @php $maxCount = max($chartCounts) ?: 1; @endphp
                    @for ($i = 0; $i < count($chartCounts); $i++)
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-blue-500 dark:bg-blue-600 rounded-t hover:bg-blue-600 dark:hover:bg-blue-500 transition"
                                 style="height: {{ ($chartCounts[$i] / $maxCount) * 100 }}%; min-height: 20px;"
                                 title="{{ $chartCounts[$i] }} bids">
                            </div>
                            <p class="text-xs text-zinc-600 dark:text-zinc-400 text-center">{{ $chartDates[$i] ?? '' }}</p>
                        </div>
                    @endfor
                @else
                    <div class="w-full flex items-center justify-center text-zinc-500 dark:text-zinc-400 h-full">
                        <p>No bid activity in the last 7 days</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
