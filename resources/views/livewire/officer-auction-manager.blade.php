<div>
    <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-1">Manage Auctions</h1>
    <p class="text-zinc-600 dark:text-zinc-400 mb-6">Create and manage your auction listings.</p>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/30 px-4 py-3 text-sm text-green-700 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form -->
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-6 shadow-sm h-fit">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                {{ $isEditing ? 'Edit Auction' : 'Create New Auction' }}
            </h2>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Title</label>
                    <input wire:model="title" type="text" placeholder="e.g. Surplus Vehicles 2020" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                    @error('title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Description</label>
                    <textarea wire:model="description" rows="4" placeholder="Detailed description of the auction items..." class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Starting Price ($)</label>
                        <input wire:model="starting_price" type="number" step="0.01" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                        @error('starting_price') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Reserve Price ($)</label>
                        <input wire:model="reserve_price" type="number" step="0.01" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error('reserve_price') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Start Time</label>
                        <input wire:model="start_time" type="datetime-local" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                        @error('start_time') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">End Time</label>
                        <input wire:model="end_time" type="datetime-local" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" required />
                        @error('end_time') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</label>
                    <select wire:model="status" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Categories</label>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border border-zinc-200 dark:border-zinc-700 rounded-md p-3">
                        @forelse($categories as $category)
                            <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}" class="rounded border-zinc-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500" />
                                {{ $category->name }}
                            </label>
                        @empty
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 col-span-2">No categories yet. Add some from the admin dashboard.</p>
                        @endforelse
                    </div>
                    @error('selectedCategories') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Upload Photos</label>
                    <input wire:model="photos" type="file" multiple accept="image/*" class="w-full text-sm text-zinc-700 dark:text-zinc-300" />
                    <div wire:loading wire:target="photos" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Uploading...</div>
                    @error('photos.*') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        {{ $isEditing ? 'Update Auction' : 'Create Auction' }}
                    </button>
                    @if($isEditing)
                        <button type="button" wire:click="resetForm" class="rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            Cancel
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listings -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Your Auctions</h2>

            @forelse($auctions as $auction)
                @php
                    $badgeClass = match($auction->status) {
                        'active' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400',
                        'draft' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400',
                        'closed' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
                        'suspended' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400',
                        default => 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300',
                    };
                @endphp
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-5 shadow-sm">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ $auction->title }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Ends: {{ $auction->end_time->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap {{ $badgeClass }}">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </div>

                    @if($auction->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($auction->categories as $category)
                                <span class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 text-xs text-blue-700 dark:text-blue-400">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($auction->status === 'suspended' && $auction->suspension_reason)
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-2">Reason: {{ $auction->suspension_reason }}</p>
                    @endif

                    <div class="text-sm text-zinc-600 dark:text-zinc-400 mt-3">
                        Start: ${{ number_format($auction->starting_price, 2) }} &middot; Current: ${{ number_format($auction->current_price, 2) }}
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button wire:click="edit({{ $auction->id }})" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                            Edit
                        </button>
                        <button wire:click="delete({{ $auction->id }})" wire:confirm="Are you sure you want to delete this auction?" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 p-8 shadow-sm text-center">
                    <p class="text-zinc-500 dark:text-zinc-400">No auctions created yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
