<x-filament::widget class="filament-stats-overview-widget" wire:init="loadData">

    <div {!! ($pollingInterval=$this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}
        >
        <x-filament::stats :columns="$this->getColumns()">
            @foreach ($this->getCachedCards() as $card)
            {{ $card }}
            @endforeach
            @if (Auth::user()->hasRole(['Super Admin', 'Instruktur']))
            <div
                class="filament-stats-card relative rounded-2xl bg-white p-6 shadow filament-stats-overview-widget-card">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        Location
                    </label>
                    <select name="id_location"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model='id_location' id="id_location" placeholder="Location">
                        <option value="">Pilih</option>
                        @foreach ($listLocation as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        </x-filament::stats>
    </div>
</x-filament::widget>