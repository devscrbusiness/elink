<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Contracts\View\View;

class EditBusinessController extends Controller
{
    public function data(Business $business): View
    {
        return $this->renderView('livewire.edit-business-data', $business);
    }

    public function location(Business $business): View
    {
        return $this->renderView('livewire.edit-business-location', $business);
    }

    public function whatsapp(Business $business): View
    {
        return $this->renderView('livewire.edit-business-whatsapp', $business);
    }

    private function renderView(string $component, Business $business): View
    {
        return view('components.layouts.admin.edit-business', compact('business'))
            ->with('user', $business->user);
    }
}
