<header class="p-4 border-b sticky top-0 bg-white z-20">
            <h1 class="text-xl font-bold leading-tight">{{ $tenant->name }}</h1>
            @if (isset($qrTable))
                <p class="text-xs text-gray-500 mt-1">
                    Meja {{ $qrTable->table_number }}
                </p>
            @endif
            <div class="mt-4 flex overflow-x-auto">
                 <button
        @click="activeCategory = 'all'"
        :class="activeCategory === 'all' 
            ? 'bg-black text-white' 
            : 'bg-white text-gray-700 border'"
        class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">
        Semua
    </button>

    @foreach ($categories as $category)
        <button
            @click="activeCategory = {{ $category->id }}"
            :class="activeCategory == {{ $category->id }} 
                ? 'bg-black text-white' 
                : 'bg-white text-gray-700 border'"
            class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">
            {{ $category->name }}
        </button>
    @endforeach
            </div>
        </header>