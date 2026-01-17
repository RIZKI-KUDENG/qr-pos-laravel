<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if(!$user->tenant_id && !$user->tenant){
            abort(403, 'You are not authorized to access this page.');
        }
        $tenant = $user->tenant ?? Tenant::find($user->tenant_id);

        $categories = $tenant->categories()
            ->with(['products' => function ($query){
                $query->where('is_active', true);
            }])
            ->get(); 

        return view('client.pos.index', compact('tenant', 'categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => [
                'required', 
                Rule::exists('products', 'id')->where(function ($query) use ($user) {
                    return $query->where('tenant_id', $user->tenant_id);
                }),
            ],
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0', 
            'cash_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:50', 
            'total_amount' => 'required|numeric|min:0',
        ]);

        $tenant = $user->tenant; 

        try {
            DB::beginTransaction();

            $order = new Order();
            $order->tenant_id = $user->tenant_id;
            $order->status = $request->status ?? 'paid'; 
            $order->order_number = 'POS-' . strtoupper(Str::random(6)) . '-' . time(); 
            $order->customer_name = $request->customer_name;
            $order->total = $request->total_amount;
            $order->save();
            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'], 
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }
            Payment::create([
                'order_id' => $order->id,
                'method' => 'cash',
                'amount' => $request->cash_amount,
                'change_amount' => $request->cash_amount - $request->total_amount,
                'status' => 'paid',
                'transaction_id' => 'TRX-' . time() . '-' . $order->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'message' => 'Order created successfully',
                'redirect_url' => route('client.order.status', [
                    'tenant' => $tenant->slug, 
                    'orderNumber' => $order->order_number
                ])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('POS Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}