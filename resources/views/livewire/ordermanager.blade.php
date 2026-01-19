<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header & Search --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order Manager</h1>
            <p class="text-gray-500 text-sm">Pantau dan kelola pesanan masuk secara real-time.</p>
        </div>
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / No Order..."
                class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black shadow-sm">
        </div>
    </div>

    {{-- filter Status --}}
    <div class="flex overflow-x-auto gap-2 mb-6 pb-2 no-scrollbar">
        @php
            $statuses = [
                'all' => 'Semua',
                'pending' => 'Pending',
                'paid' => 'Dibayar',
                'completed' => 'Selesai',
                'cancelled' => 'Batal',
            ];
        @endphp
        @foreach ($statuses as $key => $label)
            <button wire:click="setFilter('{{ $key }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors relative
                {{ $statusFilter === $key ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                {{ $label }}
                @if (isset($counts[$key]) && $key !== 'all')
                    <span
                        class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $counts[$key] }}</span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- Card Order --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($orders as $order)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
                {{-- Header Card --}}
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-lg text-gray-800">Nama : {{ $order->customer_name }}</span>
                            @if ($order->qrTable)
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-bold">Meja -
                                    {{ $order->qrTable->table_number }}</span>
                            @else
                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded font-bold"> -
                                    Takeaway / POS</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 font-mono">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @php
                        $badgeColor = match ($order->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badgeColor }}">{{ $order->status }}</span>
                </div>
                {{-- Order Items --}}
                <div class="p-4 flex-1">
                    <ul class="space-y-3">
                        @foreach ($order->orderItems as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-700">
                                    <span class="font-bold text-black">{{ $item->qty }}x</span>
                                    {{ $item->product->name ?? 'Produk Dihapus' }}
                                </span>
                                <span class="text-gray-500 font-medium">Rp
                                    {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                {{-- Footer Card --}}
                <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-lg text-gray-800">Total:</span>
                        <span class="font-bold text-2xl text-black">Rp
                            {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @if ($order->status === 'pending')
                            <button wire:click="updateStatus({{ $order->id }}, 'cancelled')"
                                class="px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                Tolak
                            </button>
                            <button wire:click="openPaymentModal({{ $order->id }})"
                                class="px-3 py-2 text-sm font-bold text-white bg-black hover:bg-gray-800 rounded-lg transition shadow-md">
                                Terima / Bayar
                            </button>
                        @elseif($order->status === 'paid')
                            <button wire:click="updateStatus({{ $order->id }}, 'completed')"
                                class="col-span-2 px-3 py-2 text-sm font-bold text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                                Sajikan
                            </button>
                        @else
                            <div class="col-span-2 text-center text-xs text-gray-400 py-2">Tidak ada aksi tersedia</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <p>Belum ada pesanan dengan status </p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>

    @if ($isPaymentModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all scale-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Pembayaran</h3>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="text-center border-b border-gray-100 pb-4">
                        <p class="text-gray-500 text-sm mb-1">Pelanggan</p>
                        <p class="font-bold text-lg">{{ $this->selectedOrder->customer_name ?? 'Tanpa Nama' }}</p>
                        <p class="text-xs text-blue-600 bg-blue-50 inline-block px-2 py-1 rounded mt-1">
                            {{ $this->selectedOrder->qrTable ? 'Meja ' . $this->selectedOrder->qrTable->table_number : 'POS / Kasir' }}
                        </p>
                    </div>
                    <div class="text-center space-y-1">
                        <p class="text-gray-500 text-sm">Total Tagihan</p>
                        <h2 class="text-4xl font-extrabold text-black">
                            Rp {{ number_format($this->selectedOrder->total, 0, ',', '.') }}
                        </h2>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Uang Tunai Diterima</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold group-focus-within:text-black">Rp</span>
                            <input type="number" wire:model.live="cashAmount"
                                class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:border-black focus:ring-black font-bold text-lg transition-shadow"
                                autofocus>
                        </div>
                        <div class="flex gap-2 mt-2 overflow-x-auto no-scrollbar pb-1">
                            <button wire:click="setQuickCash({{ $this->selectedOrder->total }})"
                                class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">
                                Uang Pas
                            </button>
                            <button wire:click="setQuickCash(50000)"
                                class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">
                                50.000
                            </button>
                            <button wire:click="setQuickCash(100000)"
                                class="flex-shrink-0 bg-gray-100 text-xs font-medium px-3 py-2 rounded-lg hover:bg-gray-200 transition border border-gray-200">
                                100.000
                            </button>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600">Kembalian</span>
                            <span
                                class="font-bold text-2xl {{ $changeAmount >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                Rp {{ number_format($changeAmount < 0 ? 0 : $changeAmount, 0, ',', '.') }}
                            </span>
                        </div>
                        @if ($changeAmount < 0)
                            <p class="text-xs text-red-500 text-right mt-1 font-medium">Uang masih kurang!</p>
                        @endif
                    </div>
                </div>
                <div class="p-6 bg-gray-600 border-t border-gray-100">
                    <button wire:click="submitPayment" wire:loading.attr="disabled" @disabled($changeAmount < 0)
                        @class([
                            'w-full py-3.5 rounded-xl font-bold text-lg transition-all shadow-lg flex justify-center',
                            'bg-gray-500 cursor-not-allowed text-white' => $changeAmount < 0,
                            'bg-white text-black hover:bg-gray-100' => $changeAmount >= 0,
                        ])>
                        <span wire:loading.remove>
                            {{ $changeAmount < 0 ? 'Uang Kurang' : 'Bayar & Proses' }}
                        </span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    @script
<script>
    $wire.on('open-print-window', (event) => {
        const url = event.url; 
        window.open(url, '_blank', 'width=400,height=600');
    });
</script>
@endscript
</div>
