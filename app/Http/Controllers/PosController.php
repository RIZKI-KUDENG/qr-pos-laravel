<?php

namespace App\Http\Controllers;


use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\StoreOrderAction;
use App\Models\Order;
use App\Models\Product;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->tenant_id && !$user->tenant) {
            abort(403, 'You are not authorized to access this page.');
        }
        $tenant = $user->tenant ?? Tenant::find($user->tenant_id);

        $categoryFilter = $request->get('category', 'all');
        $search = $request->get('search', '');

        $categories = $tenant->categories()
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

        return view(
            'client.pos.index',
            compact('tenant', 'categories', 'categoryFilter', 'search')
        );
    }
    public function printStruk($orderNumber){
        $user = Auth::user();

        $order = Order::where('order_number', $orderNumber)
        ->where('tenant_id', $user->tenant_id)
        ->with([
            'orderItems.product',
            'payment'
        ])
        ->firstOrFail();
    $tenant = $user->tenant;
        return view('client.pos.print-struk', compact('order', 'tenant'));

    }


    public function store(Request $request, StoreOrderAction $storeOrder)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => ['required', Rule::exists('products', 'id')
                ->where('tenant_id', $user->tenant_id)],
            'cart.*.qty' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'cash_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:50',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $order = $storeOrder->execute($validated, $user->tenant_id);


        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'print_url' => route('pos.print', ['orderNumber' => $order->order_number]),
            'redirect_url' => route('client.order.status', [
                'tenant' => $user->tenant->slug,
                'orderNumber' => $order->order_number,
            ]),
        ], 201);
    }
}
