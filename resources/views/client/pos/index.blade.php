<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System</title>
    
    {{-- Load Fonts & Scripts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hide scrollbar for category list but allow scrolling */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 h-screen" x-data="posPayment()">

    <div class="flex h-full">
        <main class="flex-1 h-full overflow-y-auto  relative">
            <div class="p-6 pb-24"> <div class="flex justify-between items-center mb-6 sticky top-0 bg-gray-100 z-10 py-2">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Menu Order</h1>
                        <p class="text-gray-500 text-sm">Pilih menu berdasarkan kategori</p>
                    </div>
                    
                    <div class="relative w-72">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" placeholder="Cari menu..." 
                            class="pl-10 w-full rounded-xl border-gray-200 focus:border-black focus:ring-black transition-colors"
                        >
                    </div>
                </div>

                <div class="space-y-10">
                    @forelse($categories as $category)
                        @if($category->products->count() > 0)
                            <div id="category-{{ $category->id }}">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-1 h-6 bg-black rounded-full"></div>
                                    <h2 class="text-xl font-bold text-gray-800">{{ $category->name }}</h2>
                                    <span class="text-xs font-medium text-gray-400 bg-gray-200 px-2 py-0.5 rounded-full">
                                        {{ $category->products->count() }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
                                    @foreach($category->products as $product)
                                        @include('client.pos.partials.product-card-pos', ['product' => $product])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-20">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada kategori atau produk.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </main>

        <aside class="w-[400px] bg-white border-l border-gray-200 h-full flex flex-col shadow-2xl sticky z-20 shrink-0">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
                <h2 class="font-bold text-lg text-gray-800">Current Order</h2>
                <button @click="$store.cart.clear()" class="text-red-500 text-sm hover:underline font-medium" x-show="$store.cart.items.length > 0">
                    Clear All
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <template x-if="$store.cart.items.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4 opacity-60">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <p class="font-medium">Belum ada item</p>
                    </div>
                </template>

                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100 animate-fade-in-up">
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-800 line-clamp-2" x-text="item.name"></h4>
                            <p class="text-xs text-gray-500 font-medium">
                                Rp <span x-text="formatRupiah(item.price)"></span>
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-2 bg-white rounded-lg border border-gray-200 p-1 shadow-sm">
                                <button @click="$store.cart.decrement(item.id)" class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded hover:bg-gray-200 transition-colors text-gray-600 font-bold">-</button>
                                <span class="text-sm font-bold w-4 text-center" x-text="item.qty"></span>
                                <button @click="$store.cart.increment(item.id)" class="w-6 h-6 flex items-center justify-center bg-black text-white rounded hover:bg-gray-800 transition-colors font-bold">+</button>
                            </div>
                            <p class="text-sm font-bold text-gray-800">
                                Rp <span x-text="formatRupiah(item.price * item.qty)"></span>
                            </p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-5 bg-gray-50 border-t border-gray-200 space-y-3">
                <div class="flex justify-between text-gray-500 text-sm">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-700">Rp <span x-text="formatRupiah($store.cart.total)"></span></span>
                </div>
                <div class="flex justify-between text-gray-500 text-sm">
                    <span>Pajak (10%)</span>
                    <span class="font-medium text-gray-700">Rp <span x-text="formatRupiah($store.cart.total * 0.1)"></span></span>
                </div>
                
                <div class="border-t border-gray-200 pt-3 flex justify-between items-center mb-2">
                    <span class="font-bold text-lg text-gray-800">Total</span>
                    <span class="font-bold text-2xl text-black">Rp <span x-text="formatRupiah(grandTotal())"></span></span>
                </div>

                <button 
                    @click="openPaymentModal()"
                    :disabled="$store.cart.items.length === 0"
                    :class="$store.cart.items.length === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-white hover:bg-gray-800 hover:shadow-lg'"
                    class="w-full py-4 rounded-xl font-bold text-lg transition-all flex items-center justify-center gap-2 transform active:scale-95"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Bayar Sekarang</span>
                </button>
            </div>
        </aside>
    </div>

    <div 
        x-show="isModalOpen" 
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100"
            @click.away="isModalOpen = false"
        >
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Pembayaran</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <div class="text-center space-y-1">
                    <p class="text-gray-500 text-sm">Total Tagihan</p>
                    <h2 class="text-4xl font-extrabold text-black">Rp <span x-text="formatRupiah(grandTotal())"></span></h2>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Uang Tunai Diterima</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold group-focus-within:text-black">Rp</span>
                        <input 
                            type="number" 
                            id="cashInput"
                            x-model="cashAmount"
                            @input="calculateChange()"
                            @keydown.enter="processPayment()"
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:border-black focus:ring-black font-bold text-lg transition-shadow"
                            placeholder="0"
                        >
                    </div>
                    
                    <div class="flex gap-2 mt-2 overflow-x-auto no-scrollbar pb-1">
                        <button @click="setCash(grandTotal())" class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">Uang Pas</button>
                        <button @click="setCash(50000)" class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">50.000</button>
                        <button @click="setCash(100000)" class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">100.000</button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-600">Kembalian</span>
                        <span 
                            class="font-bold text-2xl" 
                            :class="changeAmount >= 0 ? 'text-green-600' : 'text-red-500'"
                        >
                            Rp <span x-text="formatRupiah(changeAmount < 0 ? 0 : changeAmount)"></span>
                        </span>
                    </div>
                    <p x-show="changeAmount < 0" class="text-xs text-red-500 text-right mt-1 font-medium">Uang masih kurang!</p>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <button 
                    @click="processPayment()"
                    :disabled="cashAmount < grandTotal()"
                    :class="cashAmount < grandTotal() ? 'bg-gray-300 cursor-not-allowed' : 'bg-black hover:bg-gray-800 shadow-lg hover:shadow-xl'"
                    class="w-full text-white py-3.5 rounded-xl font-bold text-lg transition-all flex justify-center items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak Struk & Selesai</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posPayment', () => ({
                isModalOpen: false,
                cashAmount: '',
                changeAmount: 0,

                grandTotal() {
                    return this.$store.cart.total * 1.1; 
                },

                openPaymentModal() {
                    this.cashAmount = '';
                    this.changeAmount = -this.grandTotal();
                    this.isModalOpen = true;
                    setTimeout(() => document.getElementById('cashInput').focus(), 100);
                },

                calculateChange() {
                    const cash = Number(this.cashAmount);
                    this.changeAmount = cash - this.grandTotal();
                },

                setCash(amount) {
                    this.cashAmount = amount;
                    this.calculateChange();
                },

                processPayment() {
                    if (Number(this.cashAmount) >= this.grandTotal()) {
                        alert(`Transaksi Berhasil!\nTotal: Rp ${this.formatRupiah(this.grandTotal())}\nKembalian: Rp ${this.formatRupiah(this.changeAmount)}\n\n(Struk sedang dicetak...)`);
                        this.$store.cart.clear();
                        this.isModalOpen = false;
                    }
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(Math.floor(number));
                }
            }));
        });
    </script>
</body>
</html>