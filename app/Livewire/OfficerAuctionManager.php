<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Auction;
use App\Models\AuctionImage;
use Illuminate\Support\Facades\Auth;

class OfficerAuctionManager extends Component
{
    use WithFileUploads;

    public $auctions;
    
    public $title;
    public $description;
    public $starting_price;
    public $reserve_price;
    public $start_time;
    public $end_time;
    public $status = 'draft';
    public $photos = [];
    
    public $isEditing = false;
    public $auctionId = null;

    public function mount()
    {
        $this->loadAuctions();
    }

    public function loadAuctions()
    {
        // Officers only see their own auctions
        $this->auctions = Auction::where('created_by', Auth::id())->with('images')->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:draft,active,closed',
            'photos.*' => 'image|max:2048' // 2MB Max
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'starting_price' => $this->starting_price,
            'reserve_price' => $this->reserve_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ];

        if ($this->isEditing) {
            $auction = Auction::findOrFail($this->auctionId);
            $auction->update($data);
        } else {
            $auction = Auction::create($data);
        }

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('auctions', 'public');
                AuctionImage::create([
                    'auction_id' => $auction->id,
                    'image_path' => $path
                ]);
            }
        }

        $this->resetForm();
        $this->loadAuctions();
        session()->flash('message', 'Auction saved successfully.');
    }

    public function edit($id)
    {
        $auction = Auction::findOrFail($id);
        $this->auctionId = $auction->id;
        $this->title = $auction->title;
        $this->description = $auction->description;
        $this->starting_price = $auction->starting_price;
        $this->reserve_price = $auction->reserve_price;
        $this->start_time = $auction->start_time->format('Y-m-d\TH:i');
        $this->end_time = $auction->end_time->format('Y-m-d\TH:i');
        $this->status = $auction->status;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        Auction::findOrFail($id)->delete();
        $this->loadAuctions();
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'starting_price', 'reserve_price', 'start_time', 'end_time', 'status', 'photos', 'isEditing', 'auctionId']);
    }

    public function render()
    {
        return view('livewire.officer-auction-manager');
    }
}
