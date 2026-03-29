<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event: ') . $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                <p><strong>Note:</strong> Events cannot be edited if the event date is less than 2 days away.</p>
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg max-w-2xl mx-auto">
                <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Event Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="4" required>{{ old('description', $event->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Location -->
                    <div class="mt-4">
                        <x-input-label for="location" :value="__('Location (Venue / Address)')" />
                        <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location)" required />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <!-- Date -->
                        <div>
                            <x-input-label for="event_date" :value="__('Event Date')" />
                            <x-text-input id="event_date" class="block mt-1 w-full" type="date" name="event_date" :value="old('event_date', $event->event_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <!-- Time -->
                        <div>
                            <x-input-label for="event_time" :value="__('Event Time')" />
                            <x-text-input id="event_time" class="block mt-1 w-full" type="time" name="event_time" :value="old('event_time', \Carbon\Carbon::parse($event->event_time)->format('H:i'))" required />
                            <x-input-error :messages="$errors->get('event_time')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="mt-4">
                        <x-input-label for="image" :value="__('Event Image Banner (Leave blank to keep current)')" />
                        @if($event->image)
                            <div class="my-2">
                                <img src="{{ Storage::url($event->image) }}" class="w-32 h-32 object-cover rounded">
                            </div>
                        @endif
                        <input id="image" type="file" name="image" class="block mt-1 w-full text-gray-700 border border-gray-300 rounded-md p-2" accept="image/*" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div class="mt-4">
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft (Hide from public)</option>
                            <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Published (Visible to public)</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <x-primary-button>
                            {{ __('Update Event') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-4 flex justify-between items-center border-t pt-4">
                    <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        Cancel
                    </a>

                    <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" onsubmit="return confirm('Are you sure you want to delete this event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete Event
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
