<header class="p-4 border-b sticky top-0 bg-white z-20">
            <h1 class="text-xl font-bold leading-tight">{{ $tenant->name }}</h1>
            @if (isset($qrTable))
                <p class="text-xs text-gray-500 mt-1">
                    Meja {{ $qrTable->table_number }}
                </p>
            @endif
        </header>