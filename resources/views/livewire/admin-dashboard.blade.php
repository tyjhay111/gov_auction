<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-2">Admin Dashboard</h1>
    <p class="text-gray-600 mb-8">Manage users, moderate auctions, and view system reports.</p>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('message') }}</div>
    @endif

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="#overview" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white">Overview</a>
        <a href="#users" class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700">Users</a>
        <a href="#auctions" class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700">Auctions</a>
        <a href="#categories" class="rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700">Categories</a>
    </div>

    <div id="overview" class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-blue-100 p-3 text-blue-600">Users</div>
                <div>
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['total_users']) }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-indigo-100 p-3 text-indigo-600">Auctions</div>
                <div>
                    <div class="text-sm text-gray-500">Total Auctions</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['total_auctions']) }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-green-100 p-3 text-green-600">Revenue</div>
                <div>
                    <div class="text-sm text-gray-500">Total Revenue</div>
                    <div class="text-2xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-orange-100 p-3 text-orange-600">Bids</div>
                <div>
                    <div class="text-sm text-gray-500">Total Bids</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['total_bids']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div id="users" class="mb-10 rounded-xl border border-gray-200 bg-white p-0 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <select wire:change="changeRole({{ $user->id }}, $event.target.value)" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <option value="bidder" {{ $user->role === 'bidder' ? 'selected' : '' }}>Bidder</option>
                                    <option value="officer" {{ $user->role === 'officer' ? 'selected' : '' }}>Officer</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if(auth()->id() !== $user->id)
                                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Delete this user permanently?" class="text-red-600 hover:text-red-900">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <div id="auctions" class="mb-10 rounded-xl border border-gray-200 bg-white p-0 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($auctions as $auction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('auctions.show', $auction->id) }}" class="text-blue-600 hover:underline">{{ Str::limit($auction->title, 30) }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $auction->creator->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <select wire:change="updateAuctionStatus({{ $auction->id }}, $event.target.value)" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft" {{ $auction->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ $auction->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="closed" {{ $auction->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="deleteAuction({{ $auction->id }})" wire:confirm="Delete this auction permanently?" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $auctions->links() }}
        </div>
    </div>

    <div id="categories" class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="md:col-span-1 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Add Category</h2>
            <form wire:submit.prevent="createCategory" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Category Name</label>
                    <input wire:model="newCategoryName" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Vehicles" required />
                </div>
                <button type="submit" class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white">Create Category</button>
            </form>
        </div>
        <div class="md:col-span-2 rounded-xl border border-gray-200 bg-white p-0 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auctions</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->auctions_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Delete this category?" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
