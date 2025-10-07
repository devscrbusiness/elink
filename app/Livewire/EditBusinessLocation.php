<?php

namespace App\Livewire;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditBusinessLocation extends Component
{
    public Business $business;

    public ?string $latitude = '';
    public ?string $longitude = '';
    public ?string $detail = '';

    public function mount(Business $business)
    {
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        $this->business = $business;

        if ($location = $business->location) {
            $this->latitude = $location->latitude;
            $this->longitude = $location->longitude;
            $this->detail = $location->detail;
        }
    }

    protected function rules(): array
    {
        return [
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'detail' => 'nullable|string|max:500',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'latitude' => __('edit-business.location_latitude_label'),
            'longitude' => __('edit-business.location_longitude_label'),
            'detail' => __('edit-business.location_detail_label'),
        ];
    }

    public function save()
    {
        $this->validate();

        $this->business->location()->updateOrCreate([], [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'detail' => $this->detail,
        ]);

        session()->flash('message', __('edit-business.location_update_success'));
    }

    public function render()
    {
        return view('livewire.edit-business-location');
    }
}
