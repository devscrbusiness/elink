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

    public function mount()
    {
        if (session()->has('notification')) {
            $notification = session('notification');
            $this->dispatch('open-notification', text: $notification['text'], type: $notification['type']);
            session()->forget('notification');
        }
    }

    public function deleteUser(User $user): void
    {
        if ($user->id === Auth::id()) {
            return;
        }

        $user->delete();

        $this->dispatch('open-notification', [
            'text' => __('admin.delete_user_success'),
            'type' => 'success',
        ]);
    }

    public function toggleFavorite(User $user): void
    {
        $user->is_favorite = !$user->is_favorite;
        $user->save();

        $message = $user->is_favorite ? __('admin.user_added_to_favorites') : __('admin.user_removed_from_favorites');

        $this->dispatch('open-notification', [
            'text' => $message,
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