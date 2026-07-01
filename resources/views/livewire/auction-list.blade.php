<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <flux:heading size="xl" level="1">Browse Auctions</flux:heading>

        <div class="flex gap-4 items-center flex-wrap">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search auctions..." icon="magnifying-glass" />

            <flux:select wire:model.live="status" class="w-40">
                <option value="active">Active</option>
                <option value="closed">Closed</option>
                <option value="all">All Statuses</option>
            </flux:select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($auctions as $auction)
            <a href="{{ route('auctions.show', $auction->id) }}" class="block group">
                <flux:card class="h-full transition-all transform group-hover:-translate-y-2 group-hover:shadow-xl overflow-hidden p-0 border-0">
                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                        @if($auction->images->count() > 0)
                            <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300" alt="{{ $auction->title }}" />
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <flux:badge variant="{{ $auction->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($auction->status) }}
                            </flux:badge>
                        </div>
                        @if($auction->status === 'active' && $auction->end_time > now())
                            <div class="absolute bottom-2 left-2 bg-black/70 px-2 py-1 rounded text-xs font-semibold text-white" x-data="countdown('{{ $auction->end_time->toIso8601String() }}')">
                                <span x-text="timeRemaining"></span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col justify-between h-auto gap-3">
                        <div>
                            <flux:heading size="md" class="truncate group-hover:text-blue-600 transition">{{ $auction->title }}</flux:heading>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $auction->description }}</div>
                        </div>
                        <div class="mt-2 pt-3 border-t border-gray-100 flex justify-between items-end">
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Current Bid</div>
                                <div class="text-lg font-bold text-gray-900">${{ number_format($auction->current_price ?: $auction->starting_price, 2) }}</div>
                            </div>
                            <div class="text-xs text-gray-600 text-right">
                                {{ $auction->bids->count() }} {{ Str::plural('bid', $auction->bids->count()) }}
                            </div>
                        </div>
                    </div>
                </flux:card>
            </a>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <flux:heading size="lg">No auctions found.</flux:heading>
                <p class="mt-2">Try adjusting your filters or search term.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $auctions->links() }}
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (endTime) => ({
                timeRemaining: '',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const now = new Date().getTime();
                    const end = new Date(endTime).getTime();
                    const distance = end - now;

                    if (distance < 0) {
                        this.timeRemaining = 'Ended';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    if (days > 0) {
                        this.timeRemaining = `${days}d ${hours}h left`;
                    } else if (hours > 0) {
                        this.timeRemaining = `${hours}h ${minutes}m left`;
                    } else if (minutes > 0) {
                        this.timeRemaining = `${minutes}m ${seconds}s left`;
                    } else {
                        this.timeRemaining = `${seconds}s left`;
                    }
                }
            }));
        });
    </script>
</div>
