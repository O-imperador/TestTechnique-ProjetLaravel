<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Availability</h3>
            <div class="flex items-center space-x-4">
                <button wire:click="previousMonth" class="p-2 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <span class="text-lg font-semibold text-gray-800 w-32 text-center">{{ $monthName }}</span>
                <button wire:click="nextMonth" class="p-2 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-1 text-center text-sm mb-2">
            <div class="text-gray-500 font-medium py-2">Mon</div>
            <div class="text-gray-500 font-medium py-2">Tue</div>
            <div class="text-gray-500 font-medium py-2">Wed</div>
            <div class="text-gray-500 font-medium py-2">Thu</div>
            <div class="text-gray-500 font-medium py-2">Fri</div>
            <div class="text-gray-500 font-medium py-2">Sat</div>
            <div class="text-gray-500 font-medium py-2">Sun</div>
        </div>

        <div class="grid grid-cols-7 gap-1">
            @for ($i = 1; $i < $startDayOfWeek; $i++)
                <div class="p-2 rounded-lg bg-transparent"></div>
            @endfor

            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateString = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $isBooked = in_array($dateString, $bookedDates);
                    $isPast = \Carbon\Carbon::parse($dateString)->isPast() && !\Carbon\Carbon::parse($dateString)->isToday();
                @endphp
                <div class="p-2 flex items-center justify-center rounded-lg h-10 w-10 mx-auto
                    {{ $isBooked ? 'bg-red-100 text-red-600 font-bold' : ($isPast ? 'text-gray-400' : 'hover:bg-indigo-50 cursor-pointer text-gray-700') }}">
                    {{ $day }}
                </div>
            @endfor
        </div>
        
        <div class="mt-4 flex items-center justify-end space-x-4 text-sm text-gray-500">
            <div class="flex items-center"><div class="w-3 h-3 rounded-full bg-red-100 border border-red-200 mr-2"></div> Booked</div>
            <div class="flex items-center"><div class="w-3 h-3 rounded-full bg-white border border-gray-200 mr-2"></div> Available</div>
        </div>
    </div>
</div>
