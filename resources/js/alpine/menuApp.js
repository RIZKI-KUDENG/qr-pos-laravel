export default function menuApp(config = {}) {
    return {
        storeUrl: config.storeUrl || '',
        isProductModalOpen: false,
        isCartOpen: false,
        selectedProduct: null,
        loading: false,
        customerName: '',
        activeCategory: 'all',
        get cart() {
            return this.$store.cart.items;
        },
        get cartTotal() {
            return this.$store.cart.total;
        },
        get cartCount() {
            return this.$store.cart.count;
        },
        setActiveCategory(categoryId){
            if(this.activeCategory === "all") return true
            return this.activeCategory == categoryId
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

        submitOrder() {
    if (!this.customerName || this.customerName.trim() === '') {
        alert('Mohon isi Nama Pemesan terlebih dahulu agar kasir tidak bingung.');
        return;
    }

    this.loading = true;

    fetch(this.storeUrl, { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cart: this.cart,
            customer_name: this.customerName 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect_url) {
            this.$store.cart.clear(); 
            window.location.href = data.redirect_url;
        } else {
            alert('Gagal: ' + (data.message || 'Error tidak diketahui'));
            this.loading = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan sistem.');
        this.loading = false;
    });
}
    }
}