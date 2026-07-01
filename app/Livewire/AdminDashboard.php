<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Payment;
use App\Models\Category;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'overview';
    public $newCategoryName = '';

    // Overview Stats
    public $stats = [];
    public $categories = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::withCount('auctions')->get();
    }

    public function createCategory()
    {
        $this->validate(['newCategoryName' => 'required|string|max:255|unique:categories,name']);
        Category::create(['name' => $this->newCategoryName]);
        $this->newCategoryName = '';
        $this->loadCategories();
        session()->flash('message', 'Category created successfully.');
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
        session()->flash('message', 'Category deleted.');
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'total_auctions' => Auction::count(),
            'active_auctions' => Auction::where('status', 'active')->count(),
            'total_bids' => Bid::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];
    }

    // User Management
    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        $this->loadStats();
        session()->flash('message', 'User deleted successfully.');
    }

    public function changeRole($id, $newRole)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $newRole]);
        session()->flash('message', 'User role updated.');
    }

    // Auction Moderation
    public function updateAuctionStatus($id, $status)
    {
        $auction = Auction::findOrFail($id);
        $auction->update(['status' => $status]);
        $this->loadStats();
        session()->flash('message', "Auction status updated to {$status}.");
    }

    public function deleteAuction($id)
    {
        Auction::findOrFail($id)->delete();
        $this->loadStats();
        session()->flash('message', 'Auction deleted permanently.');
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'users' => User::latest()->paginate(10, ['*'], 'usersPage'),
            'auctions' => Auction::with('creator')->latest()->paginate(10, ['*'], 'auctionsPage'),
        ])->layout('layouts.app');
    }
}
