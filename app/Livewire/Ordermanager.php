<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Ordermanager extends Component
{
    public function markAsCompleted($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order && $order->tenant_id == Auth::user()->tenant_id) {
            $order->status = 'paid';
            $order->save();
        }
    }


    public function render()
    {
        $orders = Order::where('tenant_id', Auth::user()->tenant_id)
            ->where('status', 'pending')
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'asc')
            ->get();
        return view('livewire.ordermanager', compact('orders'));
    }
}
