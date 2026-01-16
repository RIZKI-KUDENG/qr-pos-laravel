export default function menuApp(config = {}) {
    return {
        storeUrl: config.storeUrl || '',
        isProductModalOpen: false,
        isCartOpen: false,
        selectedProduct: null,
        loading: false,
        get cart() {
            return this.$store.cart.items;
        },
        get cartTotal() {
            return this.$store.cart.total;
        },
        get cartCount() {
            return this.$store.cart.count;
        },
        openProduct(product) {
            this.selectedProduct = product
            this.isProductModalOpen = true
        },
        closeAll() {
            this.isProductModalOpen = false
            this.isCartOpen = false
        },

        incrementItem(id) {
            this.$store.cart.increment(id);
        },
        decrementItem(id) {
            this.$store.cart.decrement(id);
        },

        formatRupiah(val) {
            return new Intl.NumberFormat('id-ID').format(val)
        },

         async submitOrder() {
            if (this.cart.length === 0) return

            this.loading = true

            try {
                const res = await fetch(this.storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify({
                        cart: this.cart,
                        total_amount: this.$store.cart.total,
                    })
                })

                const data = await res.json()

                if (!res.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Gagal')
                }

                this.$store.cart.clear()
                this.isCartOpen = false
                alert('Transaksi berhasil!')

            } catch (e) {
                alert('Gagal memproses pesanan')
                console.error(e)
            } finally {
                this.loading = false
            }
        }
    }
}