<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\QrTable;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller{


public function index(Tenant $tenant, Request $request)
{
    $categoryFilter = $request->get('category', 'all');
    $search = $request->get('search', '');

    $cacheKey = "menu:tenant:{$tenant->id}:category:{$categoryFilter}:search:" . md5($search);

    $categories = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($tenant, $categoryFilter, $search) {
        return $tenant->categories()
            ->when($categoryFilter !== 'all', function ($q) use ($categoryFilter) {
                $q->where('id', $categoryFilter);
            })
            ->with(['products' => function ($query) use ($search) {
                $query->where('is_active', true)
                    ->when($search, function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            }])
            ->get();
    });

    return view('client.menu.index', compact('tenant', 'categories', 'categoryFilter', 'search'));
}

   public function table(Tenant $tenant, QrTable $qrTable)
{
    if ($qrTable->tenant_id !== $tenant->id) {
        abort(404);
    }

    $cacheKey = "menu:tenant:{$tenant->id}:table";

    $categories = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($tenant) {
        return $tenant->categories()
            ->with(['products' => fn ($q) => $q->where('is_active', true)])
            ->get();
    });

    return view('client.menu.index', compact('tenant', 'categories', 'qrTable'));
}

    public function storeOrder(Request $request, Tenant $tenant, QrTable $qrTable)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $totalAmount = 0;
            $cartItems = $request->input('cart');
            $order = new Order();
            $order->tenant_id = $tenant->id;
            $order->qr_table_id = $qrTable->id;
            $order->order_number = 'ORD-' . time() . '-' . strtoupper(Str::random(4));
            $order->customer_name = $request->customer_name ?? 'Guest';
            $order->status = 'pending';
            $order->total = 0; 
            $order->save();

            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                
                if($product->tenant_id !== $tenant->id) {
                    continue; 
                }

                $subtotal = $product->price * $item['qty'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->price, 
                ]);
            }
            $order->update(['total' => $totalAmount]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order_number' => $order->order_number,
                'redirect_url' => route('client.order.status', [
                    'tenant' => $tenant->slug, 
                    'orderNumber' => $order->order_number
                ]),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
        public function showStatus(Tenant $tenant, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->where('tenant_id', $tenant->id)
                      ->with('orderItems.product')
                      ->firstOrFail();

        return view('client.menu.order-status', compact('tenant', 'order'));
    }

    public function cancelOrder(Tenant $tenant, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
                      ->where('tenant_id', $tenant->id)
                      ->firstOrFail();

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
    }
}