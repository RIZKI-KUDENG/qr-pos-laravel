<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 5px;
            width: 58mm; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        .flex { display: flex; justify-content: space-between; }
        
        @media print {
            @page { margin: 0; size: auto; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="text-center bold">{{ $tenant->name }}</div>
    <div class="text-center">{{ $tenant->address ?? 'Alamat Tenant' }}</div>
    <div class="line"></div>
    
    <div>No: {{ $order->order_number }}</div>
    <div>Tgl: {{ $order->created_at->format('d/m/Y H:i') }}</div>
    <div>Kasir: {{ Auth::user()->name }}</div>
    <div class="line"></div>

    @foreach($order->orderItems as $item)
    <div class="flex">
        <span>{{ $item->product->name }} x{{ $item->qty }}</span>
        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <div class="line"></div>
    <div class="flex bold">
        <span>TOTAL</span>
        <span>{{ number_format($order->total, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Bayar</span>
<span>{{ number_format(optional($order->payment)->amount ?? 0, 0, ',', '.') }}</span>

    </div>
    <div class="flex">
        <span>Kembali</span>
        <span>{{ number_format($order->payment->change_amount ?? 0, 0, ',', '.') }}</span>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        Terima Kasih<br>
        Powered by Inspoflex
    </div>

    <script>
        window.onafterprint = function() {
            window.close(); 
        };
    </script>
</body>
</html>