<div class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('edit-business.sections_order_title') }}</flux:heading>
        <flux:text class="mt-1">
            <p>{{ __('edit-business.sections_order_subtitle') }}</p>
        </flux:text>
    </div>

    <div x-data="{
            dragging: null,
            drop(e, id) {
                if (this.dragging === id) return;
                let ids = Array.from(this.$refs.sectionsList.children).map(el => el.dataset.id);
                let from = ids.indexOf(this.dragging.toString());
                let to = ids.indexOf(id.toString());
                ids.splice(to, 0, ids.splice(from, 1)[0]);
                $wire.updateSectionOrder(ids);
                this.dragging = null;
            }
         }"
         class="border border-gray-200 dark:border-zinc-700 rounded-lg">
        <ul x-ref="sectionsList" class="divide-y divide-gray-200 dark:divide-zinc-700">
            @foreach ($sections as $section)
                <li draggable="true"
                    x-on:dragstart="dragging = '{{ $section['id'] }}'"
                    x-on:dragover.prevent
                    x-on:drop.prevent="drop($event, '{{ $section['id'] }}')"
                    data-id="{{ $section['id'] }}" wire:key="section-{{ $section['id'] }}"
                    class="p-4 flex items-center justify-between bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors first:rounded-t-lg last:rounded-b-lg"
                    :class="{'opacity-50': dragging === '{{ $section['id'] }}'}">
                    <div class="flex items-center gap-3">
                        <x-icon name="bars-3" class="w-5 h-5 text-gray-400 cursor-grab" />
                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $section['name'] }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('styles')
<style>
    [draggable="true"] {
        cursor: grab;
    }
</style>
@endpush