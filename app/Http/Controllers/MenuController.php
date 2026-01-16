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

class MenuController extends Controller{


    public function index(Tenant $tenant){
        $categories = $tenant->categories()->get()->with(['products' => function ($query){
            $query->where('is_active', true);
        }])->get();
        return view('client.menu.index', compact('tenant', 'categories'));
    }
    public function table(Tenant $tenant, QrTable $qrTable)
    {
        // Logika sama, tapi kita kirim data meja juga ke view
        // Pastikan QrTable memang milik Tenant ini untuk keamanan
        if ($qrTable->tenant_id !== $tenant->id) {
            abort(404);
        }

        $categories = $tenant->categories()
            ->with(['products' => fn($q) => $q->where('is_active', true)])
            ->get();

        return view('client.menu.index', compact('tenant', 'categories', 'qrTable'));
    }
    public function storeOrder(Request $request, Tenant $tenant, QrTable $qrTable)
    {
        // Validasi input
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Hitung Total di server side untuk keamanan
            $totalAmount = 0;
            $cartItems = $request->input('cart');

            // 1. Buat Order Baru
            $order = new Order();
            $order->tenant_id = $tenant->id;
            $order->qr_table_id = $qrTable->id;
            // Generate nomor order unik, contoh: ORD-TIMESTAMP-RANDOM
            $order->order_number = 'ORD-' . time() . '-' . strtoupper(Str::random(4));
            $order->status = 'pending';
            $order->total = 0; // Nanti diupdate setelah hitung item
            $order->save();

            // 2. Simpan Item Pesanan
            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                
                // Pastikan produk milik tenant ini (validasi keamanan)
                if($product->tenant_id !== $tenant->id) {
                    continue; 
                }

                $subtotal = $product->price * $item['qty'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->price, // Simpan harga saat transaksi terjadi
                ]);
            }

            // Update total harga di table orders
            $order->update(['total' => $totalAmount]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order_number' => $order->order_number,
                // 'redirect_url' => route('client.order.status', $order->id) // Jika nanti ada halaman sukses
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}