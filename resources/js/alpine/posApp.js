export default function posApp() {
    return {
        isModalOpen: false,
        cashAmount: '',
        changeAmount: 0,
        customerName: 'Pelanggan Umum', 
        loading: false,
        selectedOrder: null,
        isPaymentModalOpen: false,

        get cart() {
            return this.$store.cart.items;
        },

        get grandTotal() {
            return this.$store.cart.total;
        },

        openPaymentModal() {
           this.selectedOrder = data;     // Simpan data order (ID, Nama, Meja)
            this.grandTotal = data.total;  // Set total tagihan
            this.cashAmount = '';          // Reset input uang
            this.changeAmount = -this.grandTotal; 
            this.isPaymentModalOpen = true; // Buka modal (sesuai nama variabel di HTML)

            // Auto focus ke input
            setTimeout(() => {
                const input = document.getElementById('cashInputManager');
                if(input) input.focus();
            }, 100);
        },

        calculateChange() {
            const cash = Number(this.cashAmount);
            this.changeAmount = cash - this.grandTotal;
        },

        setCash(amount) {
            this.cashAmount = amount;
            this.calculateChange();
        },

        processPayment() {
            // Validasi Input
            if (!this.cashAmount || Number(this.cashAmount) < this.grandTotal) {
                alert('Uang tunai kurang dari total tagihan!');
                return;
            }

            if (!this.customerName.trim()) {
                alert('Nama pelanggan harus diisi!');
                return;
            }

            this.loading = true;

            fetch('/pos/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cart: this.cart,
                    cash_amount: this.cashAmount,
                    total_amount: this.grandTotal,
                    customer_name: this.customerName, 
                    status: 'paid'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data) { 
                    alert(`Transaksi Berhasil!\nKembalian: Rp ${this.formatRupiah(this.changeAmount)}`);
                    this.$store.cart.clear();
                    this.isModalOpen = false;
                    this.customerName = 'Pelanggan Umum'; 
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan validasi'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem.');
            })
            .finally(() => {
                this.loading = false;
            });
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(Math.floor(number));
        }
    };
}