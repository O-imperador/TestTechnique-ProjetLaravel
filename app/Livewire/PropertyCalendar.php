<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Property;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PropertyCalendar extends Component
{
    public Property $property;
    public $currentMonth;
    public $currentYear;

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function previousMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function render()
    {
        $startOfMonth = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $bookings = $this->property->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<', $startOfMonth)
                            ->where('end_date', '>', $endOfMonth);
                      });
            })->get();

        $bookedDates = [];
        foreach ($bookings as $booking) {
            $period = CarbonPeriod::create($booking->start_date, (clone $booking->end_date)->subDay());
            foreach ($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        $daysInMonth = $startOfMonth->daysInMonth;
        $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) - 7 (Sun)

        return view('livewire.property-calendar', [
            'daysInMonth' => $daysInMonth,
            'startDayOfWeek' => $startDayOfWeek,
            'monthName' => $startOfMonth->format('F Y'),
            'bookedDates' => $bookedDates,
        ]);
    }
}
