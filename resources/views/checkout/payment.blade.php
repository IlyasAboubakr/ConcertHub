<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Purchase') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Order Summary -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-xl mb-4">Order Summary</h3>
                        
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex justify-between items-center border-b pb-4">
                                    <div>
                                        <p class="font-bold">{{ $item->ticketType->event->title }}</p>
                                        <p class="text-sm text-gray-600">{{ $item->ticketType->name }} Ticket</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold">${{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="flex justify-between items-center pt-2 text-lg font-bold">
                                <span>Total Amount</span>
                                <span class="text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form (Simulated) -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-xl mb-4">Payment Details</h3>
                        <p class="text-sm text-gray-500 mb-6 italic">Note: This is a simulated payment flow. No actual charges will be made. You can use fake data.</p>
                        
                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf

                            <!-- Card Holder Name -->
                            <div>
                                <x-input-label for="card_holder" :value="__('Name on Card')" />
                                <x-text-input id="card_holder" class="block mt-1 w-full" type="text" name="card_holder" required autofocus placeholder="John Doe" />
                                <x-input-error :messages="$errors->get('card_holder')" class="mt-2" />
                            </div>

                            <!-- Card Number -->
                            <div class="mt-4">
                                <x-input-label for="card_number" :value="__('Card Number (16 digits)')" />
                                <x-text-input id="card_number" class="block mt-1 w-full" type="text" name="card_number" required maxlength="16" pattern="\d{16}" placeholder="1234567812345678" />
                                <x-input-error :messages="$errors->get('card_number')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <!-- Expiry -->
                                <div>
                                    <x-input-label for="expiry" :value="__('Expiry (MM/YY)')" />
                                    <x-text-input id="expiry" class="block mt-1 w-full" type="text" name="expiry" required placeholder="12/25" />
                                    <x-input-error :messages="$errors->get('expiry')" class="mt-2" />
                                </div>

                                <!-- CVV -->
                                <div>
                                    <x-input-label for="cvv" :value="__('CVV')" />
                                    <x-text-input id="cvv" class="block mt-1 w-full" type="text" name="cvv" required maxlength="3" pattern="\d{3}" placeholder="123" />
                                    <x-input-error :messages="$errors->get('cvv')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-8">
                                <x-primary-button class="w-full justify-center text-lg py-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    {{ __('Pay $') . number_format($order->total_amount, 2) }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
