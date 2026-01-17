<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex overflow-hidden" x-data="posApp()">

    <aside class="w-20 bg-black flex flex-col items-center py-6 text-white shrink-0 z-30">
        <div class="mb-8 p-2 bg-white/10 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        @include('client.pos.partials.nav-links')

        <form method="POST" action="{{ route('logout') }}" class="w-full px-2 mb-4">
            @csrf
            <button type="submit"
                class="w-full flex flex-col items-center gap-1 p-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-[10px] font-bold">Keluar</span>
            </button>
        </form>
    </aside>

    <div class="flex-1 flex h-full">
        <main class="flex-1 h-full overflow-y-auto relative bg-gray-100">
            <div class="p-6 pb-24">
                <div
                    class="flex justify-between items-center mb-6 sticky top-0 bg-gray-100/95 backdrop-blur z-10 py-4 border-b border-gray-200">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Menu Order</h1>
                        <p class="text-gray-500 text-sm">{{ now()->format('l, d F Y') }}</p>
                    </div>

                    <div class="relative w-72">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" placeholder="Cari menu..."
                            class="pl-10 w-full rounded-xl border-gray-200 focus:border-black focus:ring-black transition-colors">
                    </div>
                </div>

                <div class="space-y-10">
                    @forelse($categories as $category)
                        @if ($category->products->count() > 0)
                            <div id="category-{{ $category->id }}">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1 h-6 bg-black rounded-full"></div>
                                    <h2 class="text-xl font-bold text-gray-800">{{ $category->name }}</h2>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($category->products as $product)
                                        @include('client.pos.partials.product-card-pos', [
                                            'product' => $product,
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-20">
                            <p class="text-gray-500 font-medium">Belum ada kategori atau produk aktif.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

        @include('client.pos.partials.aside')
    </div>
    @include('client.pos.partials.modal')

   
</body>

</html>
