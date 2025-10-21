<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditUserSubscription extends Component
{
    public User $user;
    public $plans;

    public ?int $plan_id = null;
    public ?string $starts_at = null;
    public ?string $ends_at = null;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->plans = Plan::all();

        if ($subscription = $this->user->latestSubscription) {
            $this->plan_id = $subscription->plan_id;
            $this->starts_at = $subscription->starts_at->format('Y-m-d');
            $this->ends_at = $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : null;
        } else {
            // Si no hay suscripción, pre-rellenamos con el plan anual por defecto.
            $this->plan_id = 1; // ID del plan Anual
            $this->starts_at = now()->format('Y-m-d');
            $this->ends_at = now()->addYear()->format('Y-m-d');
        }
    }

    public function updatedPlanId($planId)
    {
        if (empty($planId) || empty($this->starts_at)) {
            $this->ends_at = null;
            return;
        }

        $plan = $this->plans->firstWhere('id', $planId);
        $startDate = Carbon::parse($this->starts_at);

        // Solo calcula la fecha de fin si el plan tiene una duración en meses > 0
        if ($plan && $plan->months > 0) {
            $this->ends_at = $startDate->copy()->addMonths($plan['months'])->format('Y-m-d');
        }
    }

    protected function rules(): array
    {
        return [
            'plan_id' => ['required', 'integer', Rule::in(collect($this->plans)->pluck('id'))],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'plan_id' => __('admin.table_header_plan'),
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        // Usamos create() en lugar de updateOrCreate() para mantener un historial.
        // Cada "guardado" genera un nuevo registro de suscripción.
        $this->user->subscriptions()->create([
            'plan_id' => $validated['plan_id'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);

        session()->flash('message', __('admin.subscription_updated_success'));
        $this->redirect(route('admin.subscriptions'), navigate: true);
    }

    public function render()
    {
        $subscriptionHistory = $this->user->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.edit-user-subscription', [
            'subscriptionHistory' => $subscriptionHistory
        ]);
    }
}