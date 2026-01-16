<div x-show="isCartOpen" x-transition
             class="fixed bottom-0 left-0 right-0 z-50 max-w-md mx-auto bg-white rounded-t-2xl overflow-hidden shadow-lg h-[80vh] flex flex-col">
            
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold">Pesanan Kamu</h2>
                <button @click="isCartOpen = false" class="text-gray-500 hover:text-gray-700">Tutup</button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <template x-if="cart.length === 0">
                    <div class="text-center text-gray-500 mt-10">Belum ada menu yang dipilih</div>
                </template>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex justify-between items-center border-b pb-3">
                        <div>
                            <p class="font-semibold" x-text="item.name"></p>
                            <p class="text-xs text-gray-500">Rp <span x-text="formatRupiah(item.price)"></span></p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click="decrementItem(item.id)" class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold">-</button>
                            <span x-text="item.qty" class="w-4 text-center"></span>
                            <button @click="incrementItem(item.id)" class="w-8 h-8 rounded-full bg-primary-600  flex items-center justify-center font-bold">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 border-t bg-gray-50">
                <div class="flex justify-between text-lg font-bold mb-4">
                    <span>Total</span>
                    <span>Rp <span x-text="formatRupiah(cartTotal)"></span></span>
                </div>
                <button 
                    @click="submitOrder()"
                    :disabled="loading || cart.length === 0"
                    class="w-full bg-black text-white py-3 rounded-xl font-semibold text-sm active:scale-95 disabled:opacity-50 flex justify-center items-center">
                    <span x-show="!loading">Pesan Sekarang</span>
                    <span x-show="loading">Memproses...</span>
                </button>
            </div>
        </div>