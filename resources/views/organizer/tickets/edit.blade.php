<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket Type: ') . $ticketType->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow sm:rounded-lg max-w-2xl mx-auto">
                
                <form method="POST" action="{{ route('organizer.tickets.update', [$event, $ticketType]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Ticket Type Name (e.g. VIP, General Admission)')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $ticketType->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Price -->
                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Price ($)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $ticketType->price)" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <!-- Quantity -->
                    <div class="mt-4">
                        <x-input-label for="quantity_available" :value="__('Quantity Available')" />
                        <x-text-input id="quantity_available" class="block mt-1 w-full" type="number" step="1" name="quantity_available" :value="old('quantity_available', $ticketType->quantity_available)" required />
                        <x-input-error :messages="$errors->get('quantity_available')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1">Note: Setting this to 0 will mark the ticket as Sold Out if there are no more available.</p>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('organizer.events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 mr-4">
                            Cancel
                        </a>
                        <x-primary-button>
                            {{ __('Update Ticket Type') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
