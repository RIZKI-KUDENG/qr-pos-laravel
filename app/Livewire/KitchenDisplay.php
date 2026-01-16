<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class KitchenDisplay extends Component
{
    protected $listeners = ['refreshKitchen' => '$refresh'];

    public function markAsCompleted($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order && $order->tenant_id == Auth::user()->tenant_id) {
            $order->status = 'completed'; 
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

        return view('livewire.kitchen-display', compact('orders'));
    }
}
