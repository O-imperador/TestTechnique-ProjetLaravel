<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BookingForm extends Component
{
    public Property $property;
    public $startDate = '';
    public $endDate = '';
    public $totalPrice = 0;
    public $errorMessage = '';

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            if ($end->greaterThan($start)) {
                $nights = $start->diffInDays($end);
                $this->totalPrice = $nights * $this->property->price_per_night;
                $this->errorMessage = '';
            } else {
                $this->totalPrice = 0;
            }
        }
    }

    public function updatedStartDate() { $this->calculatePrice(); }
    public function updatedEndDate() { $this->calculatePrice(); }

    public function book(BookingService $bookingService)
    {
        $this->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $bookingService->createBooking(Auth::user(), $this->property, $this->startDate, $this->endDate);
            session()->flash('message', 'Booking successful!');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.booking-form');
    }
}
