<!DOCTYPE html>
<html lang="id">

<head>
    <title>Menu - {{ $tenant->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div x-data="menuApp({ storeUrl: '{{ route('pos.store') }}'})" class="max-w-md mx-auto bg-white min-h-screen shadow-sm flex flex-col relative">
        @include('client.menu.partials.header')
        <main class="flex-1 p-4 pb-32 overflow-y-auto">
            @foreach ($categories as $category)
                <h2 class="text-base font-semibold mt-6 mb-3 text-gray-800">
                    {{ $category->name }}
                </h2>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($category->products as $product)
                        @include('client.menu.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endforeach
        </main>

        <div x-show="isProductModalOpen || isCartOpen" x-transition.opacity class="fixed inset-0 bg-black/40 z-40"
            @click="closeAllModals()"></div>
        @include('client.menu.partials.product-modal')

        @include('client.menu.partials.cart-drawer')

        <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white border-t p-4 z-30"
            x-show="!isCartOpen && !isProductModalOpen">
            <button @click="isCartOpen = true"
                class="w-full bg-black text-white py-3 rounded-xl font-semibold text-sm active:scale-95 flex justify-between px-6">
                <span>Lihat Pesanan (<span x-text="cartCount"></span>)</span>
                <span>Rp <span x-text="formatRupiah(cartTotal)"></span></span>
            </button>
            <p class="text-center text-[11px] text-gray-400 mt-3">
                Powered by <span class="font-semibold text-gray-500">Inspoflex</span>
            </p>
        </div>
    </div>

    {{-- <script>
        function appData() {
            return {
                isProductModalOpen: false,
                isCartOpen: false,
                selectedProduct: {},
                cart: [],
                loading: false,

                openProductModal(product) {
                    this.selectedProduct = product;
                    this.isProductModalOpen = true;
                },

                closeAllModals() {
                    this.isProductModalOpen = false;
                    this.isCartOpen = false;
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            qty: 1
                        });
                    }
                },

                incrementItem(id) {
                    const item = this.cart.find(item => item.id === id);
                    if (item) item.qty++;
                },

                decrementItem(id) {
                    const itemIndex = this.cart.findIndex(item => item.id === id);
                    if (itemIndex !== -1) {
                        if (this.cart[itemIndex].qty > 1) {
                            this.cart[itemIndex].qty--;
                        } else {
                            this.cart.splice(itemIndex, 1);
                        }
                    }
                },

                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                get cartCount() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                submitOrder() {
                    if (this.cart.length === 0) return;

                    this.loading = true;

                    // Ambil URL dari Route Laravel (pastikan URL ini sesuai dengan routes/web.php)
                    const submitUrl =
                        "{{ route('client.order.store', ['tenant' => $tenant->slug, 'qrTable' => $qrTable->id ?? 'null']) }}";

                    fetch(submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                cart: this.cart
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            alert('Pesanan berhasil dibuat!');
                            this.cart = []; // Kosongkan keranjang
                            this.isCartOpen = false;
                            // Opsi: Redirect ke halaman status pesanan jika ada
                            // window.location.href = data.redirect_url;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pesanan.');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }
        }
    </script> --}}
</body>

</html>
