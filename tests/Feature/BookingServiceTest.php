<?php

use App\Models\Property;
use App\Models\User;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new BookingService();
    $this->user = User::factory()->create();
    $this->property = Property::factory()->create([
        'price_per_night' => 100
    ]);
});

it('calculates total price correctly', function () {
    $startDate = Carbon::tomorrow()->toDateString();
    $endDate = Carbon::tomorrow()->addDays(3)->toDateString(); // 3 nights

    $booking = $this->service->createBooking($this->user, $this->property, $startDate, $endDate);

    expect($booking->total_price)->toBe(300.0);
});

it('prevents booking in the past', function () {
    $startDate = Carbon::yesterday()->toDateString();
    $endDate = Carbon::tomorrow()->toDateString();

    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);
})->throws(Exception::class, 'Start date cannot be in the past.');

it('prevents end date before start date', function () {
    $startDate = Carbon::tomorrow()->toDateString();
    $endDate = Carbon::today()->toDateString();

    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);
})->throws(Exception::class, 'End date must be after start date.');

it('prevents double booking when dates overlap completely', function () {
    $startDate = Carbon::tomorrow()->toDateString();
    $endDate = Carbon::tomorrow()->addDays(5)->toDateString();

    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);

    // Try exactly the same dates
    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);
})->throws(Exception::class, 'The property is already booked for these dates.');

it('prevents double booking when dates overlap partially', function () {
    $startDate = Carbon::tomorrow()->toDateString();
    $endDate = Carbon::tomorrow()->addDays(5)->toDateString();

    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);

    // Overlap at the end of the existing booking
    $newStart = Carbon::tomorrow()->addDays(3)->toDateString();
    $newEnd = Carbon::tomorrow()->addDays(8)->toDateString();

    $this->service->createBooking($this->user, $this->property, $newStart, $newEnd);
})->throws(Exception::class, 'The property is already booked for these dates.');

it('allows booking back-to-back', function () {
    $startDate = Carbon::tomorrow()->toDateString();
    $endDate = Carbon::tomorrow()->addDays(5)->toDateString();

    $this->service->createBooking($this->user, $this->property, $startDate, $endDate);

    // New booking starts exactly when the other ends
    $newStart = Carbon::tomorrow()->addDays(5)->toDateString();
    $newEnd = Carbon::tomorrow()->addDays(8)->toDateString();

    $booking = $this->service->createBooking($this->user, $this->property, $newStart, $newEnd);

    expect($booking)->toBeInstanceOf(Booking::class);
    expect(Booking::count())->toBe(2);
});
