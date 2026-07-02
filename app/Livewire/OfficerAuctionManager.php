<?php

namespace App\Livewire;

use App\Models\Auction;
use App\Models\AuctionImage;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class OfficerAuctionManager extends Component
{
    use WithFileUploads;

    public $auctions;

    public $categories;

    public $title;

    public $description;

    public $starting_price;

    public $reserve_price;

    public $start_time;

    public $end_time;

    public $status = 'draft';

    public $photos = [];

    public $selectedCategories = [];

    public $isEditing = false;

    public $auctionId = null;

    public function mount()
    {
        $this->loadAuctions();
        $this->categories = Category::orderBy('name')->get();
    }

    public function loadAuctions()
    {
        $this->auctions = Auction::where('created_by', Auth::id())->with(['images', 'categories'])->latest()->get();
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
            'photos.*' => 'image|max:2048',
            'selectedCategories' => 'array',
            'selectedCategories.*' => 'exists:categories,id',
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

        $auction->categories()->sync($this->selectedCategories);

        if (! empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('auctions', 'public');
                AuctionImage::create([
                    'auction_id' => $auction->id,
                    'image_path' => $path,
                ]);
            }
        }

        $this->resetForm();
        $this->loadAuctions();
        session()->flash('message', 'Auction saved successfully.');
    }

    public function edit($id)
    {
        $auction = Auction::with('categories')->findOrFail($id);
        $this->auctionId = $auction->id;
        $this->title = $auction->title;
        $this->description = $auction->description;
        $this->starting_price = $auction->starting_price;
        $this->reserve_price = $auction->reserve_price;
        $this->start_time = $auction->start_time->format('Y-m-d\TH:i');
        $this->end_time = $auction->end_time->format('Y-m-d\TH:i');
        $this->status = $auction->status;
        $this->selectedCategories = $auction->categories->pluck('id')->toArray();
        $this->isEditing = true;
    }

    public function delete($id)
    {
        Auction::findOrFail($id)->delete();
        $this->loadAuctions();
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'starting_price', 'reserve_price', 'start_time', 'end_time', 'status', 'photos', 'selectedCategories', 'isEditing', 'auctionId']);
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.officer-auction-manager');
    }
}
