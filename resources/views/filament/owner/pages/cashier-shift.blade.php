<x-filament-panels::page>
   <form wire:submit="save" class="space-y-6"> 
        
        {{-- Ini akan merender semua inputan yang didefinisikan di method form() atau getFormSchema() --}}
        {{ $this->form }}
        
        <div class="flex flex-wrap items-center gap-4 justify-start">
            {{-- Tombol Submit --}}
            <x-filament::button type="submit" :color="$activeShift ? 'danger' : 'success'">
                {{ $activeShift ? 'Tutup Register & Laporan' : 'Buka Register' }}
            </x-filament::button>
        </div>
        
    </form>
</x-filament-panels::page>