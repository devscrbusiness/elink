<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $query = '';

    public function deleteUser(User $user): void
    {
        if ($user->id === Auth::user()->id()) {
            return;
        }

        $user->delete();

        $this->dispatch('open-notification', [
            'text' => __('admin.delete_user_success'),
            'type' => 'success',
        ]);
    }

    public function render()
    {
        $users = User::with('business')
            ->when($this->query, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%'.$this->query.'%')
                        ->orWhere('email', 'like', '%'.$this->query.'%')
                        ->orWhereHas('business', fn ($q) => $q->where('name', 'like', '%'.$this->query.'%'));
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.admin.user-list', [
            'users' => $users,
        ]);
    }
}