<div class="py-6 px-4" wire:poll.5s> <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dapur / Kitchen Monitor</h1>
        <a href="{{ route('pos.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg font-bold">Ke POS</a>
    </div>

    @if($orders->isEmpty())
        <div class="flex flex-col items-center justify-center h-64 text-gray-400">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xl">Tidak ada pesanan baru.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-400 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-gray-400">ORDER #{{ $order->id }}</span>
                            <div class="font-bold text-lg">{{ $order->created_at->format('H:i') }}</div>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded font-bold">PENDING</span>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-800">{{ $item->product->name }}</span>
                                <span class="font-bold bg-gray-200 px-2 py-1 rounded">x{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <button 
                            wire:click="markAsCompleted({{ $order->id }})"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition"
                        >
                            Selesai Masak
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
