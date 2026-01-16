<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if(!$tenant){
            abort(403, 'You are not authorized to access this page.');
        }

        // PERBAIKAN: Hapus ->get() yang pertama
        $categories = $tenant->categories()
            ->with(['products' => function ($query){
                $query->where('is_active', true);
            }])
            ->get(); // ->get() hanya dipanggil sekali di akhir

        return view('client.pos.index', compact('tenant', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input dari Alpine.js
        $request->validate([
            'cart' => 'required|array|min:1',
            'cash_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        $user = Auth::user();
        $tenant = $user->tenant;

        try {
            DB::beginTransaction();

            // 1. Buat Order
            $order = new Order();
            $order->tenant_id = $tenant->id;
            $order->status = 'pending'; // Status awal, nanti Dapur ubah jadi 'completed'
            // $order->qr_table_id = ... (Opsional jika POS dianggap meja kasir/takeaway)
            $order->total_price = $request->total_amount;
            $order->save();

            // 2. Simpan Item Order
            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }

            // 3. Simpan Pembayaran
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'cash',
                'amount_paid' => $request->cash_amount,
                'change_amount' => $request->cash_amount - $request->total_amount,
                'status' => 'paid',
                'transaction_id' => 'POS-' . time() . '-' . $order->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}