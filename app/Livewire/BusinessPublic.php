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
    public Collection $allWebsites;
    public ?Location $location = null; // Se inicializa como null y se hace nullable

    /**
     * Mount the component with the business instance.
     */
    public function mount(Business $business): void
    {
        // Registrar la visita
        Visit::create(['business_id' => $business->id]);

        $this->business = $business->load(['whatsappLinks' => function ($query) {
            $query->where('is_public', true)->orderBy('id');
        }, 'socialLinks' => function ($query) {
            $query->where('is_public', true)->orderBy('position');
        }, 'location']);

        $this->whatsapps = $this->business->whatsappLinks;
        $this->location = $this->business->location;

        $this->prepareLinks();
    }

    protected function prepareLinks(): void
    {
        // 1. Preparamos la colección de sitios web.
        $websites = new Collection();
        if ($this->business->website) {
            $websites->push((object) [
                'url' => $this->business->website,
                'alias' => __('edit-business.visit_website_button'),
            ]);
        }

        // 2. Filtramos la colección de socialLinks para cada tipo.
        $publicSocialLinks = $this->business->socialLinks;
        $this->allWebsites = $websites->merge($publicSocialLinks->where('type', 'website'));
        $this->mails = $publicSocialLinks->where('type', 'mail');
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
