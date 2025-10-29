<?php

namespace App\Livewire;

use App\Models\Business;
use App\Models\Document;
use App\Models\SocialLink;
use App\Models\Click;
use App\Models\WhatsappLink;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public ?Business $business;

    public function mount()
    {
        $user = Auth::user();
        $this->business = $user->business()->withCount(['socialLinks', 'whatsappLinks'])->first();

        // Si es admin y no tiene empresa, lo redirigimos al dashboard de admin.
        if ($user->role === 1 && !$this->business) {
            session()->flash('notification', ['text' => __('admin.no_business_for_admin_dashboard'), 'type' => 'info']);
            $this->redirect(route('admin.dashboard'), navigate: true);
        }
    }

    public function render()
    {
        $this->business->loadCount(['socialLinks', 'whatsappLinks']);
        $totalVisits = $this->business->visits()->count();
        
        $socialLinkClicks = $this->business->clicks()->count();
        $whatsappLinkClicks = Click::where('clickable_type', WhatsappLink::class)
            ->whereIn('clickable_id', $this->business->whatsappLinks()->pluck('id'))
            ->count();
        $documentClicks = Click::where('clickable_type', Document::class)
            ->whereIn('clickable_id', $this->business->documents()->pluck('id'))
            ->count();

        $totalClicks = $socialLinkClicks + $whatsappLinkClicks + $documentClicks;

        // --- Datos para los gráficos ---
        $visitsData = $this->business->visits()
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            // Agrupamos por hora en UTC. Nota: strftime es para SQLite. Usa DATE_FORMAT para MySQL.
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as hour"), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->pluck('count', 'hour'); // La clave ahora es 'hour', que sí existe en el select.


        // --- Clics por link ---
        $socialLinksWithClicks = $this->business->socialLinks()->withCount('clicks')->get();
        $whatsappLinksWithClicks = $this->business->whatsappLinks()->withCount('clicks')->get();
        $documentsWithClicks = $this->business->documents()->withCount('clicks')->get();

        $allLinksWithClicks = $socialLinksWithClicks
            ->concat($whatsappLinksWithClicks)
            ->concat($documentsWithClicks)
            ->sortByDesc('clicks_count');

        $clickLabels = $allLinksWithClicks->pluck('alias')->map(fn($alias, $key) => $alias ?: ($allLinksWithClicks[$key]->name ?? $allLinksWithClicks[$key]->url))->toArray();
        $clickCounts = $allLinksWithClicks->pluck('clicks_count')->toArray();

        // --- Generar Código QR ---
        $renderer = new ImageRenderer(new RendererStyle(150), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString(route('business.public', $this->business->custom_link));

        return view('livewire.dashboard', [
            'totalVisits' => $totalVisits,
            'totalClicks' => $totalClicks,
            'qrCode' => $qrCode,
            'clickChart' => ['labels' => $clickLabels, 'data' => $clickCounts],
            'visitsData' => $visitsData,
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
