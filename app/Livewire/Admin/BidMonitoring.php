<?php

namespace App\Livewire\Admin;

use App\Models\Auction;
use App\Models\Bid;
use Livewire\Component;
use Livewire\WithPagination;

class BidMonitoring extends Component
{
    use WithPagination;

    public function getChartData()
    {
        $now = now();
        $labels = [];
        $counts = [];

        // Bids per minute for the last 30 minutes (crypto-style live line)
        for ($i = 29; $i >= 0; $i--) {
            $bucketStart = $now->copy()->subMinutes($i)->startOfMinute();
            $bucketEnd = $bucketStart->copy()->addMinute();

            $labels[] = $bucketStart->format('H:i');
            $counts[] = Bid::whereBetween('created_at', [$bucketStart, $bucketEnd])->count();
        }

        // Top 5 auctions by bid count
        $topAuctions = Auction::withCount('bids')
            ->orderByDesc('bids_count')
            ->take(5)
            ->get();

        return [
            'labels' => $labels,
            'counts' => $counts,
            'topAuctionLabels' => $topAuctions->pluck('title')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 20))->toArray(),
            'topAuctionCounts' => $topAuctions->pluck('bids_count')->toArray(),
        ];
    }

    public function render()
    {
        $bids = Bid::with(['user', 'auction'])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.bid-monitoring', [
            'bids' => $bids,
        ])->layout('layouts.app');
    }
}
