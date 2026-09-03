<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        @if(auth()->user()->role === 'officer')
            <livewire:officer-auction-manager />
        @elseif(auth()->user()->role === 'bidder')
            <livewire:bidder-dashboard />
        @else
            <flux:heading size="xl">Welcome to your dashboard, {{ auth()->user()->name }}</flux:heading>
        @endif
    </div>
</x-layouts::app>
