<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-64 w-full relative">
            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&sig={{ $property->id }}" alt="Property Image" class="w-full h-full object-cover">
        </div>
        <div class="p-8 border-b border-gray-100">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $property->name }}</h2>
            <p class="text-gray-600">{{ $property->description }}</p>
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                <span class="flex items-center"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> {{ $property->capacity }} Guests</span>
                <span class="flex items-center text-indigo-600 font-semibold"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> ${{ $property->price_per_night }} / night</span>
            </div>
        </div>

        <form wire:submit.prevent="book" class="p-8 bg-gray-50">
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ $errorMessage }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                    <input type="date" wire:model.live="startDate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('startDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                    <input type="date" wire:model.live="endDate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('endDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200 flex justify-between items-center">
                <span class="text-gray-700 font-medium">Total Price:</span>
                <span class="text-2xl font-bold text-indigo-600">${{ number_format($totalPrice, 2) }}</span>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm">
                Confirm Booking
            </button>
        </form>
    </div>

    <!-- Calendar Bonus Component -->
    <livewire:property-calendar :property="$property" />
</div>
