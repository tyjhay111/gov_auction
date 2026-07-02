<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('my-bids') }}" class="text-blue-600 hover:underline">&larr; Back to My Bids</a>
    </div>

    <flux:heading size="xl" level="1" class="mb-8">Complete Payment</flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Summary -->
        <div class="order-2 md:order-1">
            <flux:card class="bg-gray-50 border-gray-200">
                <flux:heading size="lg" class="mb-4">Order Summary</flux:heading>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-20 h-20 bg-gray-200 rounded overflow-hidden">
                        @if($auction->images->count() > 0)
                            <img src="{{ Storage::url($auction->images->first()->image_path) }}" class="object-cover w-full h-full" />
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">{{ $auction->title }}</div>
                        <div class="text-sm text-gray-500">Auction ID: #{{ $auction->id }}</div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Winning Bid</span>
                        <span class="font-medium">${{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Processing Fee (0%)</span>
                        <span class="font-medium">$0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2 mt-2">
                        <span>Total Due</span>
                        <span>${{ number_format($amount, 2) }}</span>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Payment Form -->
        <div class="order-1 md:order-2">
            <flux:card>
                <flux:heading size="lg" class="mb-4">Payment Details</flux:heading>
                <p class="text-sm text-gray-500 mb-6">This is a secure mock payment gateway.</p>

                <form wire:submit.prevent="processPayment" class="space-y-4">
                    <flux:input wire:model="nameOnCard" label="Name on Card" placeholder="John Doe" required />
                    
                    <flux:input wire:model="cardNumber" label="Card Number" placeholder="0000 0000 0000 0000" maxlength="19" required />
                    
                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="expiry" label="Expiry Date" placeholder="MM/YY" maxlength="5" required />
                        <flux:input wire:model="cvc" label="CVC" placeholder="123" maxlength="4" type="password" required />
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <flux:button type="submit" variant="primary" class="w-full" size="lg">
                            Pay ${{ number_format($amount, 2) }}
                        </flux:button>
                        <div class="text-xs text-center text-gray-400 mt-3 flex items-center justify-center gap-1">
                            <flux:icon.lock-closed class="w-3 h-3" />
                            Payments are processed securely
                        </div>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</div>
