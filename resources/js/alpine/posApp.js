export default function posApp() {
    return {
        isModalOpen: false,
        cashAmount: "",
        changeAmount: 0,
        customerName: "Pelanggan Umum",
        loading: false,
        search: "",
        activeCategory: "all",

        get cart() {
            return this.$store.cart.items;
        },

        get grandTotal() {
            return this.$store.cart.total;
        },
        matchesSearch(name) {
            if (!this.search) return true;
            return name.toLowerCase().includes(this.search.toLowerCase());
        },
        setActiveCategory(categoryId) {
            if (this.activeCategory === "all") return true;
            return this.activeCategory == categoryId;
        },

        openPaymentModal() {
            if (this.cart.length === 0) {
                alert("Keranjang pesanan masih kosong!");
                return;
            }
            this.cashAmount = "";
            this.changeAmount = -this.grandTotal;
            this.isModalOpen = true;
            setTimeout(() => {
                const input = document.getElementById("cashInput");
                if (input) input.focus();
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
                alert("Uang tunai kurang dari total tagihan!");
                return;
            }

            if (!this.customerName.trim()) {
                alert("Nama pelanggan harus diisi!");
                return;
            }

            this.loading = true;

            fetch("/pos/order", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    cart: this.cart,
                    cash_amount: this.cashAmount,
                    total_amount: this.grandTotal,
                    customer_name: this.customerName,
                    status: "paid",
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                   if (data.status === 'success') { 
            
            const printWindow = window.open(data.print_url, '_blank', 'width=400,height=600');
            if (!printWindow) {
                alert('Pop-up print diblokir oleh browser. Izinkan pop-up untuk situs ini.');
            }
            setTimeout(() => {
                location.reload();
            }, 1000);

        } else {
            alert("Gagal: " + (data.message || "Terjadi kesalahan validasi"));
        }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan sistem.");
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        formatRupiah(number) {
            return new Intl.NumberFormat("id-ID").format(Math.floor(number));
        },
    };
}
