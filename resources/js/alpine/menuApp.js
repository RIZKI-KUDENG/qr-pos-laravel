export default function menuApp() {
    return {
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

        async submitOrder(url) {
            if (this.cart.length === 0) return

            this.loading = true

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify({
                        cart: this.cart 
                    })
                })

                if (!res.ok) throw new Error()

                this.$store.cart.clear() 
                this.isCartOpen = false
                alert('Pesanan berhasil dibuat!')
            } catch {
                alert('Gagal memproses pesanan')
            } finally {
                this.loading = false
            }
        }
    }
}