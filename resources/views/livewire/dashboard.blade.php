<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        {{-- Tarjeta de Perfil y Acciones Rápidas --}}
        <div class="md:col-span-2 p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center gap-4">
                @if ($business->logo)
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-white dark:border-zinc-700 shadow-md" src="{{ asset('storage/' . $business->logo) }}" alt="Logo de {{ $business->name }}">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-200 dark:bg-zinc-700 text-2xl font-bold text-gray-500 dark:text-gray-300 shadow-md">
                        {{ substr($business->name, 0, 2) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $business->name }}</h2>
                    <div
                        x-data="{ url: '{{ route('business.public', $business->custom_link) }}' }"
                        class="flex items-center gap-2 mt-2"
                    >
                        <a :href="url" target="_blank" class="text-blue-500 hover:underline text-sm" x-text="url"></a>
                        <button @click="navigator.clipboard.writeText(url); $dispatch('open-notification', { text: '{{ __('dashboard.link_copied') }}', type: 'success' })" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <x-icon name="clipboard-document" class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('business.public', $business->custom_link) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <x-icon name="eye" class="w-4 h-4" />
                    {{ __('dashboard.view_public_profile') }}
                </a>
                <flux:modal.trigger name="share-profile-modal">
                    <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600">
                        <x-icon name="share" class="w-4 h-4" />
                        {{ __('dashboard.share_button') }}
                    </button>
                </flux:modal.trigger>

                <a href="{{ route('business.edit.data', $business) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600" title="{{ __('dashboard.edit_information') }}">
                    <x-icon name="pencil" class="w-4 h-4" />
                    <span class="sr-only">{{ __('dashboard.edit_information') }}</span>
                </a>
                <a href="{{ route('business.edit.social-links', $business) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-zinc-700 dark:text-gray-200 dark:hover:bg-zinc-600">
                    <x-icon name="plus" class="w-4 h-4" />
                    {{ __('dashboard.add_link') }}
                </a>
            </div>
        </div>

        {{-- Tarjeta de Código QR --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">{{ __('dashboard.qr_code') }}</h3>
            <div class="p-2 bg-white rounded-lg">
                {!! $qrCode !!}
            </div>
            <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">{{ __('dashboard.scan_to_visit') }}</p>
        </div>
    </div>

    {{-- Tarjeta de Estadísticas --}}
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('dashboard.stats') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg text-center">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $business->social_links_count }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('dashboard.social_links', $business->social_links_count) }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg text-center">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $business->whatsapp_links_count }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ trans_choice('dashboard.whatsapp_links', $business->whatsapp_links_count) }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg text-center">
                <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $totalVisits ?? 0 }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.total_visits') }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-zinc-900/50 rounded-lg text-center">
                <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $totalClicks }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('dashboard.total_clicks') }}</p>
            </div>
        </div>
        <div class="mt-4 text-right">
            <button
                wire:click="resetStats"
                wire:confirm="{{ __('dashboard.reset_stats_confirmation') }}"
                class="text-xs font-semibold text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500 transition-colors"
            >
                {{ __('dashboard.reset_stats') }}
            </button>
        </div>
    </div>

    {{-- Gráficos de Estadísticas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Gráfico de Visitas --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('dashboard.last_7_days_visits') }}</h3>
            <div class="relative h-64">
                <canvas id="visitsChart"></canvas>
            </div>
        </div>

        {{-- Gráfico de Clics por Enlace --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('dashboard.clicks_per_link') }}</h3>
            <div class="relative h-64">
                <canvas id="clicksChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Notificación para copiar enlace --}}
    <div x-data="{ show: false, text: '', type: '' }" @open-notification.window="text = $event.detail.text; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)" x-show="show" x-transition x-cloak
        class="fixed bottom-5 right-5 p-4 rounded-lg text-white"
        :class="{ 'bg-green-500': type === 'success', 'bg-red-500': type === 'error' }">
        <p x-text="text"></p>
    </div>

    {{-- Modal para compartir --}}
    @php
        $publicUrl = route('business.public', $business->custom_link);
        $shareText = urlencode("¡Mira mi perfil en eLink! " . $publicUrl);
        $shareUrl = urlencode($publicUrl);

        $shareLinks = [
            'WhatsApp' => "https://api.whatsapp.com/send?text={$shareText}",
            'Facebook' => "https://www.facebook.com/sharer/sharer.php?u={$shareUrl}",
            'X' => "https://twitter.com/intent/tweet?url={$shareUrl}&text={$shareText}",
            'Telegram' => "https://t.me/share/url?url={$shareUrl}&text={$shareText}",
            'LinkedIn' => "https://www.linkedin.com/shareArticle?mini=true&url={$shareUrl}",
        ];

        $socialColors = [
            'WhatsApp' => 'bg-green-500 hover:bg-green-600',
            'Facebook' => 'bg-blue-600 hover:bg-blue-700',
            'X' => 'bg-black hover:bg-zinc-800',
            'Telegram' => 'bg-sky-500 hover:bg-sky-600',
            'LinkedIn' => 'bg-sky-700 hover:bg-sky-800',
        ];
    @endphp
    <flux:modal name="share-profile-modal" class="min-w-[24rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('dashboard.share_modal_title') }}</flux:heading>
                <flux:text class="mt-2">
                    <p>{{ __('dashboard.share_modal_subtitle') }}</p>
                </flux:text>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($shareLinks as $network => $link)
                    <a href="{{ $link }}" target="_blank"
                       class="flex items-center justify-center gap-3 py-3 px-4 rounded-full font-semibold text-white transition {{ $socialColors[$network] ?? 'bg-gray-500 hover:bg-gray-600' }}">
                        <x-dynamic-component :component="'icons.social.' . strtolower($network)" class="w-6 h-6" />
                        <span>{{ __('dashboard.share_on', ['network' => $network]) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </flux:modal>

    @push('scripts')
    <script>
        // Almacenamiento de las instancias de los gráficos para poder destruirlas antes de volver a crearlas.
        let visitsChartInstance = null;
        let clicksChartInstance = null;

        document.addEventListener('livewire:navigated', () => {
            const darkMode = document.documentElement.classList.contains('dark');
            const gridColor = darkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const fontColor = darkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)';

            // Destrucción los gráficos anteriores si existen para evitar conflictos.
            if (visitsChartInstance) visitsChartInstance.destroy();
            if (clicksChartInstance) clicksChartInstance.destroy();

            // --- Procesamiento para el Gráfico de Visitas (con zona horaria local) ---
            const visitsDataByHourUtc = @json($visitsData); // Recibe {'YYYY-MM-DD HH:00:00': count} en UTC
            const dailyVisitsLocal = {};

            // 1. Inicializar los últimos 7 días en la zona horaria LOCAL del navegador
            for (let i = 0; i < 7; i++) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                // Usamos getFullYear, getMonth, getDate para obtener la fecha local correctamente
                const localDateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                dailyVisitsLocal[localDateString] = 0;
            }

            // 2. Re-agrupar las visitas (que vienen en UTC) al día LOCAL correspondiente
            for (const utcHourString in visitsDataByHourUtc) {
                const count = visitsDataByHourUtc[utcHourString];
                // Interpretar la fecha como UTC y convertirla a la fecha local del navegador
                const localDate = new Date(utcHourString.replace(' ', 'T') + 'Z');
                const localDateString = `${localDate.getFullYear()}-${String(localDate.getMonth() + 1).padStart(2, '0')}-${String(localDate.getDate()).padStart(2, '0')}`;

                if (dailyVisitsLocal.hasOwnProperty(localDateString)) {
                    dailyVisitsLocal[localDateString] += count;
                }
            }

            // 3. Preparar etiquetas y datos para el gráfico, ordenados por fecha
            const visitLabels = Object.keys(dailyVisitsLocal).sort().map(dateStr => {
                const date = new Date(dateStr + 'T00:00:00'); // Interpretar como fecha local para el formato de la etiqueta
                return date.toLocaleString(undefined, { weekday: 'short', day: 'numeric' });
            });
            const visitCounts = Object.keys(dailyVisitsLocal).sort().map(date => dailyVisitsLocal[date]);

            // Gráfico de Visitas
            const visitsCtx = document.getElementById('visitsChart').getContext('2d');
            visitsChartInstance = new Chart(visitsCtx, {
                type: 'line',
                data: {
                    labels: visitLabels,
                    datasets: [{
                        label: '{{ __('dashboard.visits') }}',
                        data: visitCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: fontColor, precision: 0 },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: fontColor },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Gráfico de Clics
            const clicksCtx = document.getElementById('clicksChart').getContext('2d');
            clicksChartInstance = new Chart(clicksCtx, {
                type: 'bar',
                data: {
                    labels: @json($clickChart['labels']),
                    datasets: [{
                        label: '{{ __('dashboard.clicks') }}',
                        data: @json($clickChart['data']),
                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { beginAtZero: true, ticks: { color: fontColor, precision: 0 }, grid: { color: gridColor } },
                        y: { ticks: { color: fontColor }, grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</div>