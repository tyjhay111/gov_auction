<?php

namespace App\Livewire;

use App\Models\Auction;
use Livewire\Component;
use Livewire\WithPagination;

class AuctionList extends Component
{
    use WithPagination;

    public $search = '';

    public $status = 'active';

    protected $queryString = ['search', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $auctions = Auction::query()
            ->when($this->status, function ($query) {
                if ($this->status !== 'all') {
                    $query->where('status', $this->status);
                }
            })
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('livewire.auction-list', [
            'auctions' => $auctions,
        ])->layout('layouts.app'); // Or layout('components.layouts.app') depending on the starter kit
    }
}
