<div x-show="isProductModalOpen" x-transition
            class="fixed bottom-0 left-0 right-0 z-50 max-w-md mx-auto bg-white rounded-t-2xl overflow-hidden shadow-lg">
            
            <template x-if="selectedProduct.image && selectedProduct">
                <img :src="'/storage/' + selectedProduct.image" class="w-full h-64 object-cover">
            </template>

            <div class="p-4">
                <h2 class="text-lg font-bold" x-text="selectedProduct.name"></h2>
                <p class="text-primary-600 font-bold mt-1">
                    Rp <span x-text="formatRupiah(selectedProduct.price)"></span>
                </p>
                <p class="text-sm text-gray-600 mt-3" x-text="selectedProduct.description"></p>

                <div class="mt-5 flex gap-3">
                    <button class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl" @click="isProductModalOpen = false">
                        Tutup
                    </button>
                    <button class="flex-1 border bg-primary-600 py-3 rounded-xl font-semibold hover:bg-primary-700"
                        @click="store.add(selectedProduct)" isProductModalOpen = false;">
                        + Tambah
                    </button>
                </div>
            </div>
        </div>