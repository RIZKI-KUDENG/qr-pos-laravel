<?php
namespace App\Http\Controllers;


use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\StoreOrderAction;

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
        'redirect_url' => route('client.order.status', [
            'tenant' => $user->tenant->slug,
            'orderNumber' => $order->order_number,
        ]),
    ], 201);
}
}