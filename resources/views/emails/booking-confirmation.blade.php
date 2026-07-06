<x-mail::message>
# Booking Confirmation

Hi {{ $booking->user->name }},

Your booking for **{{ $booking->property->name }}** has been successfully confirmed!

### Booking Details:
- **Check-in:** {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}
- **Check-out:** {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
- **Total Price:** ${{ number_format($booking->total_price, 2) }}

<x-mail::button :url="url('/dashboard')">
View My Bookings
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
