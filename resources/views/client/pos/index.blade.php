<!DOCTYPE html>
<html lang="id">
<head>
    <title>POS — {{ $tenant->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        /* Smooth scrolling for category anchor */
        html { scroll-behavior: smooth; }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen w-screen overflow-hidden">

<div x-data="posSystem()" class="flex h-full w-full">

    <aside class="w-20 lg:w-64 bg-white border-r flex-shrink-0 flex flex-col z-20 hidden md:flex">
        <div class="h-16 flex items-center justify-center lg:justify-start lg:px-6 border-b">
            <div class="h-10 w-10 bg-primary-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary-200">
                {{ substr($tenant->name, 0, 1) }}
            </div>
            <span class="ml-3 font-bold text-lg text-gray-800 hidden lg:block truncate">{{ $tenant->name }}</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-2">
            <div class="px-2 mb-2 hidden lg:block text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Menu
            </div>
            
            <button 
                @click="scrollTo('all')"
                :class="activeSection === 'all' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50'"
                class="w-full flex items-center justify-center lg:justify-start px-3 py-3 rounded-xl transition-all duration-200 group"
            >
                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <span class="ml-3 font-medium hidden lg:block">Semua Menu</span>
            </button>

            @foreach ($categories as $category)
            <button 
                @click="scrollTo('category-{{ $category->id }}')"
                :class="activeSection === 'category-{{ $category->id }}' ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50'"
                class="w-full flex items-center justify-center lg:justify-start px-3 py-3 rounded-xl transition-all duration-200 group"
            >
                <span class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-lg text-xs font-bold text-gray-500 group-hover:bg-white group-hover:shadow-sm">
                    {{ substr($category->name, 0, 1) }}
                </span>
                <span class="ml-3 font-medium hidden lg:block truncate text-left">{{ $category->name }}</span>
            </button>
            @endforeach
        </nav>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 bg-gray-50 relative">
        
        <header class="md:hidden h-16 bg-white border-b flex items-center justify-between px-4 z-20 flex-shrink-0">
            <div class="font-bold text-lg">{{ $tenant->name }}</div>
            <div class="text-xs font-medium bg-gray-100 px-2 py-1 rounded">{{ now()->format('H:i') }}</div>
        </header>

        <div class="h-16 bg-white border-b flex items-center px-4 lg:px-6 gap-4 flex-shrink-0 z-10">
            <div class="relative flex-1 max-w-lg">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Cari makanan atau minuman..." 
                    class="w-full pl-10 pr-4 py-2 bg-gray-100 border-transparent focus:bg-white focus:border-primary-500 focus:ring-0 rounded-xl text-sm transition-all"
                >
            </div>
        </div>

        <div id="product-container" class="flex-1 overflow-y-auto p-4 lg:p-6 pb-32 lg:pb-6 scroll-smooth">
            
            <div x-show="!search" class="space-y-8">
                @foreach ($categories as $category)
                    <div id="category-{{ $category->id }}" class="scroll-mt-20 section-category">
                        <div class="flex items-center gap-4 mb-4">
                            <h2 class="text-xl font-bold text-gray-800">{{ $category->name }}</h2>
                            <div class="h-1 w-1 rounded-full bg-gray-300"></div>
                            <span class="text-sm text-gray-400 font-medium">{{ $category->products->count() }} Items</span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($category->products as $product)
                                @include('client.pos.partials.product-card-pos', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-show="search" x-cloak>
                <h2 class="text-lg font-bold text-gray-700 mb-4">Hasil Pencarian: "<span x-text="search"></span>"</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($categories->pluck('products')->flatten() as $product)
                        <div x-show="matchSearch('{{ strtolower($product->name) }}')" class="contents">
                            @include('client.pos.partials.product-card-pos', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="h-20 md:hidden"></div>
        </div>
    </main>

    <div x-show="isCartOpen" x-transition.opacity @click="isCartOpen = false" class="fixed inset-0 bg-black/60 z-30 lg:hidden backdrop-blur-sm"></div>

    <aside 
        :class="isCartOpen ? 'translate-y-0' : 'translate-y-full lg:translate-y-0'"
        class="fixed inset-x-0 bottom-0 top-20 z-40 lg:static lg:top-auto lg:bottom-auto lg:h-full lg:w-96 bg-white border-l shadow-2xl lg:shadow-none transition-transform duration-300 ease-out flex flex-col rounded-t-3xl lg:rounded-none"
    >
        <div class="p-5 border-b flex items-center justify-between bg-white rounded-t-3xl lg:rounded-none">
            <div>
                <h2 class="font-bold text-xl text-gray-800">Current Order</h2>
                <p class="text-xs text-gray-400 mt-1">Transaction ID #{{ rand(1000,9999) }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    x-show="$store.cart.items.length > 0"
                    @click="$store.cart.clear()"
                    class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                    title="Clear Cart"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <button @click="isCartOpen = false" class="lg:hidden bg-gray-100 p-2 rounded-full text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50">
            <template x-if="$store.cart.items.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                    <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Empty" class="w-20 h-20 mb-4 grayscale opacity-50">
                    <p class="font-medium">No items added yet</p>
                </div>
            </template>

            <template x-for="item in $store.cart.items" :key="item.id">
                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex gap-3 animate-fade-in-up">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm leading-tight mb-1" x-text="item.name"></h4>
                        <p class="text-xs text-gray-500">@ <span x-text="formatRupiah(item.price)"></span></p>
                    </div>
                    
                    <div class="flex flex-col items-end justify-between">
                        <span class="font-bold text-sm text-gray-800" x-text="formatRupiah(item.price * item.qty)"></span>
                        
                        <div class="flex items-center gap-3 bg-gray-100 rounded-lg p-1 mt-2">
                            <button @click="$store.cart.decrement(item.id)" class="w-6 h-6 flex items-center justify-center bg-white rounded shadow text-gray-600 hover:text-red-500 transition">-</button>
                            <span class="text-sm font-bold w-4 text-center" x-text="item.qty"></span>
                            <button @click="$store.cart.increment(item.id)" class="w-6 h-6 flex items-center justify-center bg-primary-600 rounded shadow text-white hover:bg-primary-700 transition">+</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-5 bg-white border-t space-y-4 z-20 pb-8 lg:pb-5">
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span x-text="formatRupiah($store.cart.total)"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Tax (10%)</span>
                    <span x-text="formatRupiah($store.cart.total * 0.1)"></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-200">
                    <span class="font-bold text-gray-800 text-lg">Total</span>
                    <span class="font-bold text-2xl text-primary-600" x-text="formatRupiah($store.cart.total * 1.1)"></span>
                </div>
            </div>

            <button 
                @click="submitOrder('{{ route('pos.store') }}')"
                :disabled="loading || $store.cart.items.length === 0"
                class="w-full bg-gray-900 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-gray-200 hover:bg-black active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
                <svg x-show="!loading" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span x-show="!loading">Process Payment</span>
                <span x-show="loading">Processing...</span>
            </button>
        </div>
    </aside>

    <div x-show="!isCartOpen" class="lg:hidden fixed bottom-4 left-4 right-4 z-30">
        <button 
            @click="isCartOpen = true"
            class="w-full bg-gray-900 text-white py-4 px-6 rounded-2xl shadow-2xl flex items-center justify-between active:scale-95 transition-transform"
        >
            <div class="flex items-center gap-3">
                <div class="bg-primary-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm" x-text="$store.cart.count">0</div>
                <span class="font-semibold">View Order</span>
            </div>
            <span class="font-bold text-lg" x-text="formatRupiah($store.cart.total)"></span>
        </button>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('posSystem', () => ({
            search: '',
            isCartOpen: false,
            activeSection: 'all',
            loading: false,

            init() {
                
            },

            scrollTo(id) {
                this.activeSection = id;
                if(id === 'all') {
                    document.getElementById('product-container').scrollTo({top: 0, behavior: 'smooth'});
                    return;
                }
                const el = document.getElementById(id);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            },

            matchSearch(productName) {
                if (this.search === '') return true;
                return productName.includes(this.search.toLowerCase());
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { maximumSignificantDigits: 3 }).format(number); // Simplified formatting
                // Or use standard: return new Intl.NumberFormat('id-ID').format(number);
            },

            async submitOrder(url) {
                if (this.$store.cart.items.length === 0) return;
                
                this.loading = true;
                // Simulasi delay biar keliatan loading
                // await new Promise(r => setTimeout(r, 500)); 

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cart: this.$store.cart.items
                        })
                    });

                    const data = await res.json();
                    
                    if (!res.ok) throw new Error(data.message || 'Something went wrong');

                    // Success
                    alert('Order Berhasil! Transaksi tercatat.');
                    this.$store.cart.clear();
                    this.isCartOpen = false;

                } catch (err) {
                    console.error(err);
                    alert('Gagal memproses pesanan: ' + err.message);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

</body>
</html>