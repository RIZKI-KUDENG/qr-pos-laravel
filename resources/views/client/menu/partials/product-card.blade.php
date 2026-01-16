<div
    class="border rounded-xl overflow-hidden bg-white active:scale-[0.98] transition cursor-pointer"
    @click="openProduct({{ json_encode($product) }})"
>
    <div class="aspect-square bg-gray-200">
        @if ($product->image)
            <img
                src="{{ asset('storage/'.$product->image) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover"
            >
        @endif
    </div>

    <div class="p-3">
        <h3 class="font-semibold text-sm line-clamp-2">
            {{ $product->name }}
        </h3>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-primary-600 font-bold text-sm">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>
