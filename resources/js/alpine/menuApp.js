export default function menuApp() {
    return {
        isProductModalOpen: false,
        isCartOpen: false,
        selectedProduct: null,
        loading: false,

        openProduct(product) {
            this.selectedProduct = product
            this.isProductModalOpen = true
        },

        closeAll() {
            this.isProductModalOpen = false
            this.isCartOpen = false
        },

        formatRupiah(val) {
            return new Intl.NumberFormat('id-ID').format(val)
        },

        async submitOrder(url) {
            if (Alpine.store('cart').items.length === 0) return

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
                        cart: Alpine.store('cart').items
                    })
                })

                if (!res.ok) throw new Error()

                Alpine.store('cart').clear()
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
