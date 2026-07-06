<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">My Bookings</h2>
        </div>

        <div class="p-8">
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if($bookings->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p>You have no bookings yet.</p>
                    <a href="{{ route('properties.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">Browse properties</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="border border-gray-200 rounded-xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center hover:shadow-md transition">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $booking->property->name }}</h3>
                                <div class="mt-2 text-sm text-gray-600 space-y-1">
                                    <p><span class="font-medium">Dates:</span> {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</p>
                                    <p><span class="font-medium">Total:</span> ${{ number_format($booking->total_price, 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 flex flex-col items-end space-y-3">
                                @if($booking->status === 'cancelled')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold uppercase tracking-wider">Cancelled</span>
                                @elseif(\Carbon\Carbon::parse($booking->end_date)->isPast())
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold uppercase tracking-wider">Completed</span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold uppercase tracking-wider">Confirmed</span>
                                    
                                    @can('cancel', $booking)
                                    <button 
                                        wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="Are you sure you want to cancel this booking? This action cannot be undone."
                                        class="text-sm text-red-600 hover:text-red-800 font-medium underline"
                                    >
                                        Cancel Booking
                                    </button>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
