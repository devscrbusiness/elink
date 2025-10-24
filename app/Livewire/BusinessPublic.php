<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Click;
use App\Models\SocialLink;
use App\Models\WhatsappLink;
use Livewire\Attributes\Layout;
use App\Models\Location;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

#[Layout('components.layouts.public')]

class BusinessPublic extends Component
{
    /**
     * The business instance.
     */
    public Business $business;

    public Collection $whatsapps;
    public Collection $socialNetworks;
    public Collection $mails;
    public Collection $websites;
    public Collection $others;

    /**
     * Mount the component with the business instance.
     */
    public function mount(Business $business): void
    {
        // Registrar la visita
        Visit::create(['business_id' => $business->id]);

        $this->business = $business->load(['whatsappLinks' => function ($query) {
            $query->where('is_public', true)->orderBy('position');
        }, 'socialLinks' => function ($query) { // Carga de enlaces sociales
            $query->where('is_public', true)->orderBy('position');
        }, 'locations']); // Carga la nueva relación 'locations'

        $this->whatsapps = $this->business->whatsappLinks;

        $this->prepareLinks();
    }

    protected function prepareLinks(): void
    {
        // Filtramos la colección de socialLinks para cada tipo.
        $publicSocialLinks = $this->business->socialLinks;
        $this->websites = $publicSocialLinks->where('type', 'website');
        $this->mails = $publicSocialLinks->where('type', 'mail');
        $this->others = $publicSocialLinks->where('type', 'other');
        $this->socialNetworks = $publicSocialLinks->whereNotIn('type', ['website', 'mail', 'other']);
    }

    public function logClick(int $linkId, string $linkType)
    {
        $model = null;
        if ($linkType === 'social') {
            $model = SocialLink::class;
        } elseif ($linkType === 'whatsapp') {
            $model = WhatsappLink::class;
        }

        if ($model) {
            $link = $model::findOrFail($linkId);
            $link->clicks()->create();
            return $link->url;
        }

        return null;
    }

    /**
     * Render the component's view.
     */
    public function render(): View
    {
        return view('livewire.business-public');
    }
}
