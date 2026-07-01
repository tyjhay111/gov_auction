<div class="container mx-auto px-4 py-8">
    <flux:heading size="xl" level="1" class="mb-2">Auction Analytics</flux:heading>
    <flux:subheading size="lg" class="mb-8">Detailed insights into your auction performance.</flux:subheading>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <flux:card class="border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Auctions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAuctions }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex gap-4 text-sm">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Active: {{ $activeAuctions }}</span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Closed: {{ $closedAuctions }}</span>
            </div>
        </flux:card>

        <flux:card class="border-l-4 border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </flux:card>

        <flux:card class="border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bids</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBids }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m2-1l-2-1m2 1v2.5" />
                </svg>
            </div>
        </flux:card>

        <flux:card class="border-l-4 border-l-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg. Bids/Auction</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $averageBidsPerAuction }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Performing Auctions -->
        <div class="lg:col-span-2">
            <flux:card>
                <flux:heading size="lg" class="mb-4">Top Performing Auctions</flux:heading>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Title</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Final Price</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Bids</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topAuctions as $auction)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <a href="{{ route('auctions.show', $auction->id) }}" class="text-blue-600 hover:text-blue-800 font-medium truncate block">
                                            {{ $auction->title }}
                                        </a>
                                    </td>
                                    <td class="text-right py-3 px-4 font-semibold text-gray-900">${{ number_format($auction->current_price, 2) }}</td>
                                    <td class="text-right py-3 px-4 text-gray-600">{{ $auction->bids->count() }}</td>
                                    <td class="text-right py-3 px-4">
                                        <flux:badge variant="{{ $auction->status === 'active' ? 'success' : 'danger' }}" size="sm">
                                            {{ ucfirst($auction->status) }}
                                        </flux:badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">
                                        No auctions yet. Create your first auction to see analytics.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </flux:card>
        </div>

        <!-- Recent Activity -->
        <div>
            <flux:card>
                <flux:heading size="lg" class="mb-4">Recent Bids</flux:heading>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentBids as $bid)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition">
                            <div class="flex justify-between items-start mb-1">
                                <p class="font-semibold text-sm text-gray-900 truncate">{{ $bid->user->name }}</p>
                                <p class="font-bold text-sm text-green-600">${{ number_format($bid->amount, 2) }}</p>
                            </div>
                            <p class="text-xs text-gray-600 truncate">{{ $bid->auction->title }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $bid->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-6">No bids yet on your auctions.</p>
                    @endforelse
                </div>
            </flux:card>
        </div>
    </div>

    <!-- Bid Activity Chart -->
    <div class="mt-6">
        <flux:card>
            <flux:heading size="lg" class="mb-4">7-Day Bid Activity</flux:heading>
            <div class="h-64 flex items-end gap-2 bg-gradient-to-t from-gray-50 to-transparent p-4 rounded-lg" id="bidChart">
                @php
                    $maxCount = max($chartCounts) ?: 1;
                @endphp
                @for ($i = 0; $i < count($chartCounts); $i++)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t hover:from-blue-600 hover:to-blue-500 transition"
                             style="height: {{ ($chartCounts[$i] / $maxCount) * 100 }}%; min-height: 20px;"
                             title="{{ $chartCounts[$i] }} bids">
                        </div>
                        <p class="text-xs text-gray-600 text-center">{{ $chartDates[$i] ?? '' }}</p>
                    </div>
                @endfor
                @if(empty($chartCounts))
                    <div class="w-full flex items-center justify-center text-gray-500 h-full">
                        <p>No bid activity in the last 7 days</p>
                    </div>
                @endif
            </div>
        </flux:card>
    </div>
</div>
