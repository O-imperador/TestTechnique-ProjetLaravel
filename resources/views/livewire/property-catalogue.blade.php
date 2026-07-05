<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 p-6 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center">
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Properties</label>
            <input type="text" wire:model.live="search" placeholder="E.g. Beach house..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
            <input type="date" wire:model.live="startDate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
            <input type="date" wire:model.live="endDate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($properties as $index => $property)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="h-56 bg-gray-200 relative">
                    <!-- Random architectural image based on property ID -->
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80&sig={{ $property->id }}" alt="Property Image" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-800 shadow">
                        <span class="flex items-center"><svg class="w-3 h-3 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.9</span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $property->name }}</h3>
                    <p class="text-gray-500 mb-4 line-clamp-2">{{ $property->description }}</p>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-lg font-semibold text-indigo-600">${{ $property->price_per_night }} <span class="text-sm text-gray-500 font-normal">/ night</span></span>
                        <span class="text-sm text-gray-500">Up to {{ $property->capacity }} guests</span>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('properties.book', $property) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                No properties found matching your criteria.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $properties->links() }}
    </div>
</div>
