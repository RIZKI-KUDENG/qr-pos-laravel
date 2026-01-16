<div 
    @click="$store.cart.add({{ json_encode($product) }})"
    class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-lg hover:border-primary-200 transition-all duration-300 cursor-pointer group flex flex-col h-full relative overflow-hidden"
>
    <div class="aspect-square rounded-xl bg-gray-100 overflow-hidden relative mb-3">
        @if ($product->image)
            <img 
                src="{{ asset('storage/'.$product->image) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="hidden absolute inset-0 flex items-center justify-center text-gray-300 bg-gray-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @else
             <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        <div class="absolute bottom-2 right-2 translate-y-10 group-hover:translate-y-0 transition-transform duration-300">
            <div class="bg-black text-white p-2 rounded-full shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-1 group-hover:text-primary-600 transition-colors">
            {{ $product->name }}
        </h3>
        <p class="mt-auto font-bold text-primary-600 text-sm">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>
    </div>
</div>