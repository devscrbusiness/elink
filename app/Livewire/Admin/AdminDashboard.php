<?php

namespace App\Livewire\Admin;

use App\Models\Business;
use App\Models\SocialLink;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WhatsappLink;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $totalBusinesses = Business::count();

        // Contar suscripciones activas.
        // Se considera activa si no tiene fecha de fin o si la fecha de fin es futura.
        $activeSubscriptions = Subscription::where(function ($query) {
            $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
        })->count();

        $totalSocialLinks = SocialLink::count();
        $totalWhatsappLinks = WhatsappLink::count();
        $totalLinks = $totalSocialLinks + $totalWhatsappLinks;

        $recentUsers = User::with('business')->latest()->take(5)->get();

        return view('livewire.admin.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalBusinesses' => $totalBusinesses,
            'activeSubscriptions' => $activeSubscriptions,
            'totalLinks' => $totalLinks,
            'recentUsers' => $recentUsers,
        ]);
    }
}