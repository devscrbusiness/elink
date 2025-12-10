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

    public $range = 'all';
    public $rangeText = '';

    public $totalVisits, $totalClicks, $qrCode, $clickChart, $visitsData;

    public function mount()
    {
        $user = Auth::user();
        $this->business = $user->business()->withCount(['socialLinks', 'whatsappLinks'])->first();

        // Si es admin y no tiene empresa, lo redirigimos al dashboard de admin.
        if ($user->role === 1 && !$this->business) {
            session()->flash('notification', ['text' => __('admin.no_business_for_admin_dashboard'), 'type' => 'info']);
            $this->redirect(route('admin.dashboard'), navigate: true);
        }

        $this->updateStats();
    }

    public function updatedRange($value)
    {
        $this->range = $value;
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->rangeText = $this->getRangeText();
        $startDate = $this->getStartDate();

        $this->business->loadCount(['socialLinks', 'whatsappLinks']);

        // --- Cuentas totales ---
        $visitsQuery = $this->business->visits();
        $socialLinkClicksQuery = $this->business->clicks();
        $whatsappLinkClicksQuery = Click::where('clickable_type', WhatsappLink::class)->whereIn('clickable_id', $this->business->whatsappLinks()->pluck('id'));
        $documentClicksQuery = Click::where('clickable_type', Document::class)->whereIn('clickable_id', $this->business->documents()->pluck('id'));

        if ($startDate) {
            $visitsQuery->where('created_at', '>=', $startDate);
            $socialLinkClicksQuery->where('clicks.created_at', '>=', $startDate);
            $whatsappLinkClicksQuery->where('created_at', '>=', $startDate);
            $documentClicksQuery->where('created_at', '>=', $startDate);
        }

        $this->totalVisits = $visitsQuery->count();
        $socialLinkClicks = $socialLinkClicksQuery->count();
        $whatsappLinkClicks = $whatsappLinkClicksQuery->count();
        $documentClicks = $documentClicksQuery->count();
        $this->totalClicks = $socialLinkClicks + $whatsappLinkClicks + $documentClicks;

        // --- Datos para los gráficos ---
        $visitsQueryForChart = $this->business->visits();
        if ($startDate) {
            $visitsQueryForChart->where('created_at', '>=', $startDate);
        }
        $this->visitsData = $visitsQueryForChart->select(DB::raw("DATE(created_at) as date"), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');


        // --- Clics por link ---
        $withCountClicks = function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        };

        $clicksCountConstraint = $startDate ? ['clicks' => $withCountClicks] : 'clicks';

        $socialLinksWithClicks = $this->business->socialLinks()->withCount($clicksCountConstraint)->get();
        $whatsappLinksWithClicks = $this->business->whatsappLinks()->withCount($clicksCountConstraint)->get();
        $documentsWithClicks = $this->business->documents()->withCount($clicksCountConstraint)->get();

        $allLinksWithClicks = $socialLinksWithClicks
            ->concat($whatsappLinksWithClicks)
            ->concat($documentsWithClicks)
            ->sortByDesc('clicks_count');

        $clickLabels = $allLinksWithClicks->pluck('alias')->map(fn ($alias, $key) => $alias ?: ($allLinksWithClicks[$key]->name ?? $allLinksWithClicks[$key]->url))->toArray();
        $clickCounts = $allLinksWithClicks->pluck('clicks_count')->toArray();
        $this->clickChart = ['labels' => $clickLabels, 'data' => $clickCounts];

        // --- Generar Código QR ---
        $renderer = new ImageRenderer(new RendererStyle(150), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $this->qrCode = $writer->writeString(route('business.public', $this->business->custom_link));

        // Despacha un evento al navegador con los nuevos datos para los gráficos
        $this->dispatch('update-charts', visitsData: $this->visitsData, clickChart: $this->clickChart, range: $this->range);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalVisits' => $this->totalVisits,
            'totalClicks' => $this->totalClicks,
            'qrCode' => $this->qrCode,
            'clickChart' => $this->clickChart,
            'visitsData' => $this->visitsData,
            'rangeText' => $this->rangeText,
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
        $documentIds = $this->business->documents()->pluck('id');

        Click::where('clickable_type', SocialLink::class)
             ->whereIn('clickable_id', $socialLinkIds)
             ->delete();

        Click::where('clickable_type', WhatsappLink::class)
             ->whereIn('clickable_id', $whatsappLinkIds)
             ->delete();

        Click::where('clickable_type', Document::class)
            ->whereIn('clickable_id', $documentIds)
            ->delete();

        $this->updateStats();
    }

    private function getStartDate()
    {
        return match ($this->range) {
            '7_days' => Carbon::now()->subDays(7)->startOfDay(),
            '15_days' => Carbon::now()->subDays(15)->startOfDay(),
            '1_month' => Carbon::now()->subMonth()->startOfDay(),
            '1_year' => Carbon::now()->subYear()->startOfDay(),
            default => null,
        };
    }

    private function getRangeText()
    {
        return match ($this->range) {
            '7_days' => __('dashboard.last_7_days'),
            '15_days' => __('dashboard.last_15_days'),
            '1_month' => __('dashboard.last_month'),
            '1_year' => __('dashboard.last_year'),
            default => __('dashboard.all_time'),
        };
    }
}
