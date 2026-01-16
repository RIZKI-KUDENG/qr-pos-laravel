<!DOCTYPE html>
<html lang="id">
<head>
    <title>Menu - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">
        <div class="p-4 bg-white border-b sticky top-0 z-10">
            <h1 class="text-xl font-bold">{{ $tenant->name }}</h1>
            @if(isset($qrTable))
                <p class="text-sm text-gray-500">Meja: {{ $qrTable->table_number}}</p>
            @endif
        </div>

        <div class="p-4 pb-20">
            @foreach($categories as $category)
                <h2 class="text-lg font-semibold mt-4 mb-2 text-primary-600">{{ $category->name }}</h2>
                <div class="space-y-4">
                    @foreach($category->products as $product)
                        <div class="flex items-center justify-between border p-3 rounded-lg shadow-sm bg-white">
                            <div class="flex-1">
                                <h3 class="font-medium">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $product->description }}</p>
                                <p class="text-primary-600 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <img src="{{ $product->image }}" alt="">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm ml-2">
                                + Add
                            </button>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="fixed bottom-0 left-0 w-full p-4 bg-white border-t flex justify-center max-w-md mx-auto right-0">
            <button class="w-full bg-black text-white py-3 rounded-lg font-bold">
                Lihat Pesanan
            </button>
        </div>
    </div>
</body>
</html>