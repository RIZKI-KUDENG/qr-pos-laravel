<div x-show="isModalOpen" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100"
            @click.away="isModalOpen = false">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Pembayaran</h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <div class="text-center space-y-1">
                    <p class="text-gray-500 text-sm">Total Tagihan</p>
                    <h2 class="text-4xl font-extrabold text-black">Rp <span
                            x-text="formatRupiah(grandTotal())"></span></h2>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Uang Tunai Diterima</label>
                    <div class="relative group">
                        <span
                            class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold group-focus-within:text-black">Rp</span>
                        <input type="number" id="cashInput" x-model="cashAmount" @input="calculateChange()"
                            @keydown.enter="processPayment()"
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:border-black focus:ring-black font-bold text-lg transition-shadow"
                            placeholder="0">
                    </div>

                    <div class="flex gap-2 mt-2 overflow-x-auto no-scrollbar pb-1">
                        <button @click="setCash(grandTotal())"
                            class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">Uang
                            Pas</button>
                        <button @click="setCash(50000)"
                            class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">50.000</button>
                        <button @click="setCash(100000)"
                            class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">100.000</button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-600">Kembalian</span>
                        <span class="font-bold text-2xl"
                            :class="changeAmount >= 0 ? 'text-green-600' : 'text-red-500'">
                            Rp <span x-text="formatRupiah(changeAmount < 0 ? 0 : changeAmount)"></span>
                        </span>
                    </div>
                    <p x-show="changeAmount < 0" class="text-xs text-red-500 text-right mt-1 font-medium">Uang masih
                        kurang!</p>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <button @click="processPayment()" :disabled="cashAmount < grandTotal()"
                    :class="cashAmount < grandTotal() ? 'bg-gray-300 cursor-not-allowed' :
                        'bg-black hover:bg-gray-800 shadow-lg hover:shadow-xl'"
                    class="w-full text-white py-3.5 rounded-xl font-bold text-lg transition-all flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    <span>Cetak Struk & Selesai</span>
                </button>
            </div>
        </div>
    </div>