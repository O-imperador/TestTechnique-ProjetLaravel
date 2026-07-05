<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    /**
     * @throws Exception
     */
    public function createBooking(User $user, Property $property, string $startDate, string $endDate): Booking
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($start->isPast() && !$start->isToday()) {
            throw new Exception("Start date cannot be in the past.");
        }

        if ($end->lessThanOrEqualTo($start)) {
            throw new Exception("End date must be after start date.");
        }

        return DB::transaction(function () use ($user, $property, $start, $end) {
            // Lock the property row to prevent double booking in concurrent requests
            $lockedProperty = Property::where('id', $property->id)->lockForUpdate()->first();

            // Check for overlaps: NewStart < ExistingEnd AND NewEnd > ExistingStart
            $overlap = Booking::where('property_id', $lockedProperty->id)
                ->where('start_date', '<', $end)
                ->where('end_date', '>', $start)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($overlap) {
                throw new Exception("The property is already booked for these dates.");
            }

            // Calculate total price
            $nights = $start->diffInDays($end);
            if ($nights === 0) {
                $nights = 1; // Minimum 1 night
            }
            $totalPrice = $nights * $lockedProperty->price_per_night;

            return Booking::create([
                'user_id' => $user->id,
                'property_id' => $lockedProperty->id,
                'start_date' => $start,
                'end_date' => $end,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
            ]);
        });
    }
}
