<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Booking;

class UserBookings extends Component
{
    use AuthorizesRequests;

    public function cancelBooking(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        $booking->update(['status' => 'cancelled']);
        
        session()->flash('message', 'Booking cancelled successfully.');
    }

    public function render()
    {
        $bookings = Auth::user()->bookings()->with('property')->orderBy('start_date', 'desc')->get();
        return view('livewire.user-bookings', compact('bookings'));
    }
}
