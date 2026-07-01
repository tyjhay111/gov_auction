<div>
    <flux:heading size="xl" level="1">Manage Auctions</flux:heading>
    <flux:subheading size="lg" class="mb-6">Create and manage your auction listings.</flux:subheading>

    @if (session()->has('message'))
        <flux:toast variant="success">{{ session('message') }}</flux:toast>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Form -->
        <flux:card>
            <flux:heading size="lg">{{ $isEditing ? 'Edit Auction' : 'Create New Auction' }}</flux:heading>
            <form wire:submit.prevent="save" class="mt-4 space-y-4">
                <flux:input wire:model="title" label="Title" placeholder="e.g. Surplus Vehicles 2020" required />
                <flux:textarea wire:model="description" label="Description" placeholder="Detailed description of the auction items..." required />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="starting_price" type="number" step="0.01" label="Starting Price ($)" required />
                    <flux:input wire:model="reserve_price" type="number" step="0.01" label="Reserve Price ($)" />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="start_time" type="datetime-local" label="Start Time" required />
                    <flux:input wire:model="end_time" type="datetime-local" label="End Time" required />
                </div>

                <flux:select wire:model="status" label="Status">
                    <option value="draft">Draft</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                </flux:select>

                <flux:input wire:model="photos" type="file" label="Upload Photos" multiple accept="image/*" />

                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary">{{ $isEditing ? 'Update Auction' : 'Create Auction' }}</flux:button>
                    @if($isEditing)
                        <flux:button type="button" wire:click="resetForm" variant="secondary">Cancel</flux:button>
                    @endif
                </div>
            </form>
        </flux:card>

        <!-- Listings -->
        <div class="space-y-4">
            <flux:heading size="lg">Your Auctions</flux:heading>
            
            @forelse($auctions as $auction)
                <flux:card class="flex flex-col gap-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <flux:heading size="md">{{ $auction->title }}</flux:heading>
                            <flux:subheading>Ends: {{ $auction->end_time->format('M d, Y H:i') }}</flux:subheading>
                        </div>
                        <flux:badge variant="{{ $auction->status === 'active' ? 'success' : ($auction->status === 'draft' ? 'warning' : 'danger') }}">
                            {{ ucfirst($auction->status) }}
                        </flux:badge>
                    </div>
                    <div class="text-sm">
                        Start: ${{ number_format($auction->starting_price, 2) }} | 
                        Current: ${{ number_format($auction->current_price, 2) }}
                    </div>
                    <div class="flex gap-2 mt-2">
                        <flux:button size="sm" wire:click="edit({{ $auction->id }})">Edit</flux:button>
                        <flux:button size="sm" variant="danger" wire:click="delete({{ $auction->id }})" wire:confirm="Are you sure you want to delete this auction?">Delete</flux:button>
                    </div>
                </flux:card>
            @empty
                <flux:card>
                    <div class="text-center text-gray-500">No auctions created yet.</div>
                </flux:card>
            @endforelse
        </div>
    </div>
</div>
