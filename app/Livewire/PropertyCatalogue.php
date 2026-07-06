<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Property;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PropertyCatalogue extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate = '';
    public $endDate = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function render()
    {
        $query = Property::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->startDate && $this->endDate) {
            $query->whereDoesntHave('bookings', function ($q) {
                $q->where('start_date', '<', $this->endDate)
                  ->where('end_date', '>', $this->startDate)
                  ->where('status', '!=', 'cancelled');
            });
        }

        return view('livewire.property-catalogue', [
            'properties' => $query->paginate(6)
        ]);
    }
}
