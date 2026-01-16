<!DOCTYPE html>
<html lang="id">

<head>
    <title>Menu - {{ $tenant->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div x-data="{
        open: false,
        product: {}
    }" class="max-w-md mx-auto bg-white min-h-screen shadow-sm flex flex-col">
        <header class="p-4 border-b sticky top-0 bg-white z-20">
            <h1 class="text-xl font-bold leading-tight">{{ $tenant->name }}</h1>
            @if (isset($qrTable))
                <p class="text-xs text-gray-500 mt-1">
                    Meja {{ $qrTable->table_number }}
                </p>
            @endif
        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-4 pb-32">
            @foreach ($categories as $category)
                <h2 class="text-base font-semibold mt-6 mb-3 text-gray-800">
                    {{ $category->name }}
                </h2>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($category->products as $product)
                        <div class="border rounded-xl overflow-hidden bg-white active:scale-[0.98] transition cursor-pointer"
                            @click="
        open = true;
        product = {
            name: '{{ $product->name }}',
            price: '{{ number_format($product->price, 0, ',', '.') }}',
            description: `{!! addslashes($product->description) !!}`,
            image: '{{ $product->image ? asset('storage/' . $product->image) : '' }}'
        }
    ">
                            <!-- IMAGE -->
                            <div class="aspect-square bg-gray-200">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>

                            <!-- CONTENT -->
                            <div class="p-3">
                                <h3 class="font-semibold text-sm line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-primary-600 font-bold text-sm">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                    <span class="text-xs text-gray-400">
                                        Detail
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </main>
        <!-- BACKDROP -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40 z-40" @click="open = false"></div>

        <!-- MODAL -->
        <div x-show="open" x-transition
            class="fixed bottom-0 left-0 right-0 z-50 max-w-md mx-auto bg-white rounded-t-2xl overflow-hidden">

            <!-- IMAGE -->
            <template x-if="product.image">
                <img :src="product.image" alt="" class="w-full h-64 object-cover">
            </template>

            <!-- CONTENT -->
            <div class="p-4">
                <h2 class="text-lg font-bold" x-text="product.name"></h2>

                <p class="text-primary-600 font-bold mt-1">
                    Rp <span x-text="product.price"></span>
                </p>

                <template x-if="product.description">
                    <p class="text-sm text-gray-600 mt-3">
                        <span x-text="product.description"></span>
                    </p>
                </template>

                <!-- ACTION -->
                <div class="mt-5 flex gap-3">
                    <button class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl" @click="open = false">
                        Tutup
                    </button>

                    <button class="flex-1 border bg-primary-600  py-3 rounded-xl font-semibold">
                        + Tambah
                    </button>
                </div>
            </div>
        </div>


        <!-- ACTION BAR -->
        <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white border-t p-4 z-30">
            <button class="w-full bg-black text-white py-3 rounded-xl font-semibold text-sm active:scale-95">
                Lihat Pesanan
            </button>

            <!-- BRANDING -->
            <p class="text-center text-[11px] text-gray-400 mt-3">
                Powered by <span class="font-semibold text-gray-500">Inspoflex</span>
            </p>
        </div>

    </div>
</body>

</html>
