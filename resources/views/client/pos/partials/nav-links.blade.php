<nav class="flex-1 flex flex-col gap-6 w-full px-2">
            <a href="{{ route('pos.index') }}"
                class="flex flex-col items-center gap-1 p-3 rounded-xl {{ request()->routeIs('pos.index') ? 'bg-white text-black' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
                <svg id='Money_Cashier_24' width='24' height='24' viewBox='0 0 24 24'
                    xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                    <rect width='24' height='24' stroke='none' fill='#000000' opacity='0' />
                    <g transform="matrix(1.43 0 0 1.43 12 12)">
                        <g style="">
                            <g transform="matrix(1 0 0 1 0 4)">
                                <line
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    x1="-0.5" y1="0" x2="0.5" y2="0" />
                            </g>
                            <g transform="matrix(1 0 0 1 3 -3)">
                                <line
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    x1="0" y1="1" x2="0" y2="-1" />
                            </g>
                            <g transform="matrix(1 0 0 1 3 -5.25)">
                                <path
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    transform=" translate(-10, -1.75)"
                                    d="M 12 1.75 C 12 2.4403559372884915 11.440355937288492 3 10.75 3 L 9.25 3 C 8.559644062711508 3 8 2.4403559372884915 8 1.75 C 8 1.0596440627115082 8.559644062711508 0.5 9.25 0.5 L 10.75 0.5 C 11.440355937288492 0.5 12 1.059644062711508 12 1.7499999999999998 Z"
                                    stroke-linecap="round" />
                            </g>
                            <g transform="matrix(1 0 0 1 -3 -3.75)">
                                <polyline
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    points="1.5,1.75 1.5,-1.75 -1.5,-1.75 -1.5,1.75 " />
                            </g>
                            <g transform="matrix(1 0 0 1 0 4)">
                                <rect
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    x="-6.5" y="-2.5" rx="1" ry="1" width="13" height="5" />
                            </g>
                            <g transform="matrix(1 0 0 1 0 -0.25)">
                                <path
                                    style="stroke: rgb(0,0,0); stroke-width: 1; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                    transform=" translate(-7, -6.75)"
                                    d="M 12.5 8.5 L 12.5 6 C 12.5 5.447715250169207 12.052284749830793 5 11.5 5 L 2.5 5 C 1.9477152501692065 5 1.5 5.447715250169207 1.5 6 L 1.5 8.5"
                                    stroke-linecap="round" />
                            </g>
                        </g>
                    </g>
                </svg>
                <span class="text-[10px] font-bold">Kasir</span>
            </a>

            <a href="{{ route('kitchen.index') }}"
                class="flex flex-col items-center gap-1 p-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span class="text-[10px] font-bold">Dapur</span>
            </a>
            <a href="{{ route('order.index') }}" 
            class="flex flex-col items-center gap-1 p-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414a1 1 0 00-.707-.293H4" />
                </svg>
                <span class="text-[10px] font-bold">Order</span>
            </a>

            <a href="{{ route('products.index') }}"
                class="flex flex-col items-center gap-1 p-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="text-[10px] font-bold">Produk</span>
            </a>
        </nav>