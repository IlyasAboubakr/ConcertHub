<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 bg-white shadow sm:rounded-lg max-w-2xl mx-auto">
                
                <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Event Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="4" required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Location -->
                    <div class="mt-4">
                        <x-input-label for="location" :value="__('Location (Venue / Address)')" />
                        <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <!-- Date -->
                        <div>
                            <x-input-label for="event_date" :value="__('Event Date')" />
                            <x-text-input id="event_date" class="block mt-1 w-full" type="date" name="event_date" :value="old('event_date')" required min="{{ date('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <!-- Time -->
                        <div>
                            <x-input-label for="event_time" :value="__('Event Time')" />
                            <x-text-input id="event_time" class="block mt-1 w-full" type="time" name="event_time" :value="old('event_time')" required />
                            <x-input-error :messages="$errors->get('event_time')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Event Image Banner')" />
                        <input id="image" type="file" name="image" class="block mt-1 w-full text-gray-700 border border-gray-300 rounded-md p-2" accept="image/*" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hide from public)</option>
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Visible to public)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Tickets Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Ticket Types (Required)</h3>
                        <p class="text-sm text-gray-500 mb-4">Add at least one ticket type for this event.</p>
                        
                        <div id="tickets-container" class="space-y-4">
                            <!-- Default Ticket Row -->
                            <div class="ticket-row border border-gray-200 rounded-lg p-4 bg-gray-50 flex flex-col md:flex-row gap-4 items-end">
                                <div class="w-full md:w-2/5">
                                    <x-input-label :value="__('Ticket Name (e.g. VIP, General)')" />
                                    <x-text-input class="block mt-1 w-full" type="text" name="tickets[0][name]" required />
                                </div>
                                <div class="w-full md:w-1/4">
                                    <x-input-label :value="__('Price ($)')" />
                                    <x-text-input class="block mt-1 w-full" type="number" step="0.01" min="0" name="tickets[0][price]" required />
                                </div>
                                <div class="w-full md:w-1/4">
                                    <x-input-label :value="__('Quantity Available')" />
                                    <x-text-input class="block mt-1 w-full" type="number" min="1" name="tickets[0][quantity]" required />
                                </div>
                                <div class="w-full md:w-auto">
                                    <button type="button" class="remove-ticket px-3 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200 hidden">Remove</button>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="add-ticket" class="mt-4 px-4 py-2 bg-indigo-50 text-indigo-600 rounded border border-indigo-200 hover:bg-indigo-100 font-semibold text-sm">
                            + Add Another Ticket Type
                        </button>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-200">
                        <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                            Cancel
                        </a>
                        <x-primary-button>
                            {{ __('Create Event') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ticketCount = 1;
            const container = document.getElementById('tickets-container');
            const addButton = document.getElementById('add-ticket');

            addButton.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'ticket-row border border-gray-200 rounded-lg p-4 bg-gray-50 flex flex-col md:flex-row gap-4 items-end';
                row.innerHTML = `
                    <div class="w-full md:w-2/5">
                        <label class="block font-medium text-sm text-gray-700">Ticket Name (e.g. VIP, General)</label>
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="text" name="tickets[${ticketCount}][name]" required>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="block font-medium text-sm text-gray-700">Price ($)</label>
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="number" step="0.01" min="0" name="tickets[${ticketCount}][price]" required>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="block font-medium text-sm text-gray-700">Quantity Available</label>
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="number" min="1" name="tickets[${ticketCount}][quantity]" required>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="button" class="remove-ticket px-3 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200">Remove</button>
                    </div>
                `;
                container.appendChild(row);
                ticketCount++;
                updateRemoveButtons();
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-ticket')) {
                    e.target.closest('.ticket-row').remove();
                    updateRemoveButtons();
                }
            });

            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.ticket-row');
                const removeBtns = container.querySelectorAll('.remove-ticket');
                if (rows.length > 1) {
                    removeBtns.forEach(btn => btn.classList.remove('hidden'));
                } else {
                    removeBtns.forEach(btn => btn.classList.add('hidden'));
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
