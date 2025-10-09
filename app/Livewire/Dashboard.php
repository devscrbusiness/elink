<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\SocialLink;
use App\Models\Click;
use App\Models\WhatsappLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public ?Business $business;

    public function mount()
    {
        $this->business = Auth::user()->business()->withCount(['socialLinks', 'whatsappLinks'])->first();
    }

    public function render()
    {
        $this->business->loadCount(['socialLinks', 'whatsappLinks']);
        $totalVisits = $this->business->visits()->count();
        
        $socialLinkClicks = $this->business->clicks()->count();
        $whatsappLinkClicks = Click::where('clickable_type', WhatsappLink::class)->whereIn('clickable_id', $this->business->whatsappLinks()->pluck('id'))->count();
        $totalClicks = $socialLinkClicks + $whatsappLinkClicks;

        // --- Datos para los gráficos ---
        $visitsData = $this->business->visits()
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $visitLabels = [];
        $visitCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $visitLabels[] = Carbon::parse($date)->translatedFormat('D d');
            $visitCounts[] = $visitsData->get($date)->count ?? 0;
        }

        // --- Clics por link ---
        $socialLinksWithClicks = $this->business->socialLinks()->withCount('clicks')->get();
        $whatsappLinksWithClicks = $this->business->whatsappLinks()->withCount('clicks')->get();
        $allLinksWithClicks = $socialLinksWithClicks->concat($whatsappLinksWithClicks)->sortByDesc('clicks_count');

        $clickLabels = $allLinksWithClicks->pluck('alias')->map(fn($alias, $key) => $alias ?: $allLinksWithClicks[$key]->url)->toArray();
        $clickCounts = $allLinksWithClicks->pluck('clicks_count')->toArray();

        return view('livewire.dashboard', [
            'totalVisits' => $totalVisits,
            'totalClicks' => $totalClicks,
            'visitChart' => ['labels' => $visitLabels, 'data' => $visitCounts],
            'clickChart' => ['labels' => $clickLabels, 'data' => $clickCounts],
        ]);
    }

    /**
     * Reinicia los contadores de visitas y clics de la empresa.
     */
    public function resetStats()
    {
        $this->business->visits()->delete();

        $socialLinkIds = $this->business->socialLinks()->pluck('id');
        $whatsappLinkIds = $this->business->whatsappLinks()->pluck('id');

        Click::where('clickable_type', SocialLink::class)
             ->whereIn('clickable_id', $socialLinkIds)
             ->delete();

        Click::where('clickable_type', WhatsappLink::class)
             ->whereIn('clickable_id', $whatsappLinkIds)
             ->delete();
    }
}
