<aside class="w-[380px] bg-white border-l border-gray-200 h-full flex flex-col shadow-2xl sticky z-20 shrink-0">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
        <h2 class="font-bold text-lg text-gray-800">Current Order</h2> <button @click="$store.cart.clear()"
            class="text-red-500 text-sm hover:underline font-medium" x-show="$store.cart.items.length > 0">
            Clear All </button>
    </div>
    <div class="flex-1 overflow-y-auto p-4 space-y-4"> <template x-if="$store.cart.items.length === 0">
            <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4 opacity-60">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="font-medium">Belum ada item</p>
            </div>
        </template> <template x-for="item in $store.cart.items" :key="item.id">
            <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100 animate-fade-in-up">
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 line-clamp-2" x-text="item.name"></h4>
                    <p class="text-xs text-gray-500 font-medium"> Rp <span x-text="formatRupiah(item.price)"></span>
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <div class="flex items-center gap-2 bg-white rounded-lg border border-gray-200 p-1 shadow-sm">
                        <button @click="$store.cart.decrement(item.id)"
                            class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded hover:bg-gray-200 transition-colors text-gray-600 font-bold">-</button>
                        <span class="text-sm font-bold w-4 text-center" x-text="item.qty"></span> <button
                            @click="$store.cart.increment(item.id)"
                            class="w-6 h-6 flex items-center justify-center bg-black text-white rounded hover:bg-gray-800 transition-colors font-bold">+</button>
                    </div>
                    <p class="text-sm font-bold text-gray-800"> Rp <span
                            x-text="formatRupiah(item.price * item.qty)"></span> </p>
                </div>
            </div>
        </template> </div>
    <div class="p-5 bg-gray-50 border-t border-gray-200 space-y-3">
        <div class="flex justify-between text-gray-500 text-sm"> <span>Subtotal</span> <span
                class="font-medium text-gray-700">Rp <span x-text="formatRupiah($store.cart.total)"></span></span>
        </div>
        <div class="border-t border-gray-200 pt-3 flex justify-between items-center mb-2"> <span
                class="font-bold text-lg text-gray-800">Total</span> <span class="font-bold text-2xl text-black">Rp
                <span x-text="formatRupiah(grandTotal)"></span></span> </div> <button @click="openPaymentModal()"
            :disabled="$store.cart.items.length === 0"
            :class="$store.cart.items.length === 0 ? 'bg-gray-300 cursor-not-allowed' :
                'bg-white hover:bg-gray-800 hover:shadow-lg'"
            class="w-full py-4 rounded-xl font-bold text-lg transition-all flex items-center justify-center gap-2 transform active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg> <span>Bayar Sekarang</span> </button>
    </div>
</aside>
