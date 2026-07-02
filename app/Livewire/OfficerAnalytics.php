<?php

namespace App\Livewire;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OfficerAnalytics extends Component
{
    public function render()
    {
        $userId = Auth::id();

        $auctions = Auction::where('created_by', $userId)->with('bids.user')->get();

        $totalAuctions = $auctions->count();
        $activeAuctions = $auctions->where('status', 'active')->count();
        $closedAuctions = $auctions->where('status', 'closed')->count();

        $totalRevenue = $auctions->sum('current_price');
        $totalBids = $auctions->sum(function ($auction) {
            return $auction->bids->count();
        });

        $averageBidsPerAuction = $totalAuctions > 0 ? round($totalBids / $totalAuctions, 2) : 0;

        $topAuctions = $auctions
            ->sortByDesc(function ($auction) {
                return $auction->current_price;
            })
            ->take(5);

        $bidActivityData = DB::table('bids')
            ->join('auctions', 'bids.auction_id', '=', 'auctions.id')
            ->where('auctions.created_by', $userId)
            ->where('bids.created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(bids.created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy(DB::raw('DATE(bids.created_at)'))
            ->orderBy('date')
            ->get();

        $chartDates = $bidActivityData->pluck('date')->map(fn ($date) => date('M d', strtotime($date)))->toArray();
        $chartCounts = $bidActivityData->pluck('count')->toArray();

        $recentBids = Bid::whereIn('auction_id', $auctions->pluck('id'))
            ->with('user', 'auction')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.officer-analytics', [
            'totalAuctions' => $totalAuctions,
            'activeAuctions' => $activeAuctions,
            'closedAuctions' => $closedAuctions,
            'totalRevenue' => $totalRevenue,
            'totalBids' => $totalBids,
            'averageBidsPerAuction' => $averageBidsPerAuction,
            'topAuctions' => $topAuctions,
            'recentBids' => $recentBids,
            'chartDates' => $chartDates,
            'chartCounts' => $chartCounts,
        ])->layout('layouts.app');
    }
}
