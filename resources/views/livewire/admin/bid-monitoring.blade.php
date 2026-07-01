<div wire:poll.10s class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Bid Monitoring</h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-8">Live bidding activity across all auctions.</p>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div wire:ignore
            x-data="{
                chart: null,
                init() {
                    const ctx = this.$refs.lineCanvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: { labels: [], datasets: [{ label: 'Bids per minute', data: [], borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.3 }] },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                    });
                    this.refresh();
                    setInterval(() => this.refresh(), 5000);
                },
                async refresh() {
                    const data = await $wire.getChartData();
                    this.chart.data.labels = data.labels;
                    this.chart.data.datasets[0].data = data.counts;
                    this.chart.update();
                }
            }"
            class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Bids Over Time (last 30 min)</h3>
            <canvas x-ref="lineCanvas" height="120"></canvas>
        </div>

        <div wire:ignore
            x-data="{
                chart: null,
                init() {
                    const ctx = this.$refs.barCanvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: { labels: [], datasets: [{ label: 'Bids', data: [], backgroundColor: '#6366f1' }] },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                    });
                    this.refresh();
                    setInterval(() => this.refresh(), 5000);
                },
                async refresh() {
                    const data = await $wire.getChartData();
                    this.chart.data.labels = data.topAuctionLabels;
                    this.chart.data.datasets[0].data = data.topAuctionCounts;
                    this.chart.update();
                }
            }"
            class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-4 shadow-sm">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Top Auctions by Bid Count</h3>
            <canvas x-ref="barCanvas" height="120"></canvas>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Bidder</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Auction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Placed</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($bids as $bid)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $bid->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                <a href="{{ route('auctions.show', $bid->auction_id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ \Illuminate\Support\Str::limit($bid->auction->title ?? 'Deleted auction', 30) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">${{ number_format($bid->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $bid->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">No bids placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $bids->links() }}
        </div>
    </div>
</div>
