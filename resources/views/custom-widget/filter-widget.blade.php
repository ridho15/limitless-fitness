<x-filament::widget class="filament-stats-overview-widget">
    <div class="rounded overflow-hidden shadow-lg mb-4" style="border-radius: 1rem !important">
        <div class="px-6 pt-4 pb-2"
            style="padding-top: 1rem; padding-bottom: 1rem; background-color: white; overflow: auto !important; max-height: 1200px;">
            <div class="grid @if(Auth::user()->hasRole(['Super Admin', 'Instruktur'])) grid-cols-3 @else grid-cols-2 @endif gap-4"
                style="margin-bottom: 20px">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal_awal">
                        Start Date
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model="tanggal_awal" id="tanggal_awal" type="date" placeholder="Tanggal">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal_akhir">
                        End Date
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        wire:model='tanggal_akhir' id="tanggal_akhir" type="date" placeholder="Tanggal">
                </div>
                @if (Auth::user()->hasRole(['Super Admin', 'Instruktur']))
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
                @endif
            </div>
            <div class="text-center">
                <div class="text-center pb-3" wire:loading>
                    Loading ...
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
            jQuery(document).ready(function() {
                jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
            });
        </script>
    </div>
    <div {!! ($pollingInterval=$this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}
        >
        <x-filament::stats :columns="$this->getColumns()">
            @foreach ($this->getCachedCards() as $card)
            {{ $card }}
            @endforeach
        </x-filament::stats>
    </div>
</x-filament::widget>