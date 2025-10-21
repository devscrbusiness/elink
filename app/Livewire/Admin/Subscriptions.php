<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use WithPagination;

    public string $query = '';
    public string $statusFilter = 'all'; // 'all', 'active', 'expired'

    public function updatingQuery()
    {
        $this->resetPage();
    }

    public function setStatusFilter(string $status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with(['business', 'latestSubscription.plan'])
            ->when($this->query, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->query}%")
                        ->orWhere('email', 'like', "%{$this->query}%")
                        ->orWhereHas('business', fn ($q) => $q->where('name', 'like', "%{$this->query}%"));
                });
            })
            ->when($this->statusFilter === 'active', function ($query) {
                $query->whereHas('latestSubscription', function ($q) {
                    $q->where(function ($subQ) {
                        $subQ->where('ends_at', '>=', now())->orWhereNull('ends_at');
                    });
                });
            })
            ->when($this->statusFilter === 'expired', function ($query) {
                $query->whereHas('latestSubscription', function ($q) {
                    $q->where('ends_at', '<', now());
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.admin.subscriptions', [
            'users' => $users,
            'statusFilter' => $this->statusFilter,
        ]);
    }

}