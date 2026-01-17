<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
        
        <div class="p-6 text-center border-b border-gray-100">
            @if($order->status == 'pending')
                <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Menunggu Pembayaran</h1>
                <p class="text-gray-500 text-sm mt-1">Silahkan menuju kasir untuk membayar.</p>
            @elseif($order->status == 'paid')
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Pembayaran Berhasil</h1>
                <p class="text-gray-500 text-sm mt-1">Pesanan Anda sedang disiapkan dapur.</p>
            @elseif($order->status == 'cancelled')
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Pesanan Dibatalkan</h1>
            @endif
        </div>

        <div class="p-6 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Order</span>
                <span class="text-xs font-bold text-gray-800">{{ $order->order_number }}</span>
            </div>
            
            <div class="flex justify-between items-center mb-6 p-3 bg-white rounded-lg border">
                 <span class="text-sm text-gray-500">Nama Pemesan</span>
                 <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
            </div>

            <div class="space-y-3">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $item->qty }}x {{ $item->product->name }}</span>
                    <span class="font-medium">Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="p-6">
            @if($order->status == 'pending')
                <form action="{{ route('client.order.cancel', ['tenant' => $tenant->slug, 'orderNumber' => $order->order_number]) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                    @csrf
                    <button type="submit" class="w-full py-3 rounded-xl border-2 border-red-100 text-red-600 font-bold hover:bg-red-50 transition text-sm">
                        Batalkan Pesanan
                    </button>
                </form>
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-400">Tunjukkan halaman ini ke kasir.</p>
                    <button onclick="window.location.reload()" class="mt-2 text-indigo-600 text-sm font-medium hover:underline">Refresh Status</button>
                </div>
            @else
                <a href="{{ route('client.menu', ['tenant' => $tenant->slug, 'qrTable' => $order->qr_table_id ?? 'default']) }}" class="block w-full py-3 bg-black text-white text-center rounded-xl font-bold text-sm">
                    Pesan Lagi
                </a>
            @endif
        </div>

    </div>

</body>
</html>