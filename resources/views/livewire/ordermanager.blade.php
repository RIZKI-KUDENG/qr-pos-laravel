<div class="p-6 bg-gray-50 min-h-screen" wire:poll.5s>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order Manager</h1>
            <p class="text-gray-500 text-sm">Pantau dan kelola pesanan masuk secara real-time.</p>
        </div>
        <div class="w-full md:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / No Order..." class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black shadow-sm">
        </div>
    </div>

    <div class="flex overflow-x-auto gap-2 mb-6 pb-2 no-scrollbar">
        @php
            $statuses = [
                'all' => 'Semua',
                'pending' => 'Pending',
                'paid' => 'Dibayar',
                'completed' => 'Selesai',
                'cancelled' => 'Batal'
            ];
        @endphp

        @foreach ($statuses as $key => $label)
            <button 
                wire:click="setFilter('{{ $key }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-colors relative
                {{ $statusFilter === $key ? 'bg-black text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                {{ $label }}
                @if(isset($counts[$key]) && $key !== 'all')
                    <span class="ml-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $counts[$key] }}</span>
                @endif
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($orders as $order)
            <div wire:key="order-{{ $order->id }}" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full" x-data="{ open: false }">
                
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-lg text-gray-800">{{ $order->customer_name ?? 'Tanpa Nama' }}</span>
                            @if($order->qrTable)
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-bold">Meja {{ $order->qrTable->name }}</span>
                            @else
                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded font-bold">POS / Kasir</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500 font-mono">{{ $order->order_number }} • {{ $order->created_at->format('H:i') }}</span>
                    </div>
                    
                    @php
                        $badgeColor = match($order->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'cooking' => 'bg-orange-100 text-orange-800',
                            'served' => 'bg-blue-100 text-blue-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badgeColor }}">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="p-4 flex-1">
                    <ul class="space-y-3">
                        @foreach ($order->orderItems->take(3) as $item)
                            <li class="flex justify-between text-sm">
                                <span class="text-gray-700">
                                    <span class="font-bold text-black">{{ $item->qty }}x</span> 
                                    {{ $item->product->name ?? 'Produk Dihapus' }}
                                </span>
                                <span class="text-gray-500 font-medium">
                                    Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @if($order->orderItems->count() > 3)
                        <div x-show="open" x-transition class="mt-3 space-y-3 pt-3 border-t border-dashed border-gray-200">
                            @foreach ($order->orderItems->skip(3) as $item)
                                <li class="flex justify-between text-sm">
                                    <span class="text-gray-700">
                                        <span class="font-bold text-black">{{ $item->qty }}x</span> 
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </span>
                                    <span class="text-gray-500 font-medium">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </span>
                                </li>
                            @endforeach
                        </div>
                        <button @click="open = !open" class="mt-3 text-xs text-blue-600 font-medium hover:underline flex items-center gap-1">
                            <span x-text="open ? 'Tutup Detail' : 'Lihat {{ $order->orderItems->count() - 3 }} item lainnya...'"></span>
                        </button>
                    @endif
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500">Total Tagihan</span>
                        <span class="font-bold text-lg text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @if($order->status === 'pending')
                            <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" 
                                    wire:confirm="Yakin batalkan pesanan ini?"
                                    class="px-3 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                Tolak
                            </button>
                            <button wire:click="updateStatus({{ $order->id }}, 'paid')" 
                                    class="px-3 py-2 text-sm font-bold text-white bg-black hover:bg-gray-800 rounded-lg transition shadow-md">
                                Terima & Masak
                            </button>
                        @elseif($order->status === 'paid')
                            <button wire:click="updateStatus({{ $order->id }}, 'completed')" 
                                    class="col-span-2 px-3 py-2 text-sm font-bold text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                                Sajikan / Selesai
                            </button>
                        @else
                            <div class="col-span-2 text-center text-xs text-gray-400 py-2">
                                Tidak ada aksi tersedia
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p>Belum ada pesanan dengan status <span class="font-bold">"{{ ucfirst($statusFilter) }}"</span></p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>