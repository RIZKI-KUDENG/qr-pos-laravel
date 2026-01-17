<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Ordermanager extends Component
{
    use WithPagination;

    public $statusFilter = 'all'; 
    public $search = '';

    protected $queryString = ['statusFilter', 'search'];

    public function setFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage(); 
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::where('tenant_id', Auth::user()->tenant_id)->find($orderId);

        if ($order) {
            $order->update(['status' => $newStatus]);
        }
    }

    public function render()
    {
        $user = Auth::user();

        $orders = Order::with(['orderItems.product', 'qrTable'])
    ->where('tenant_id', $user->tenant_id)
    ->when($this->statusFilter !== 'all', function ($query) {
        $query->where('status', $this->statusFilter);
    })
    ->when($this->search, function ($query) {
        $query->where(function ($q) {
            $q->where('customer_name', 'like', '%' . $this->search . '%')
              ->orWhere('order_number', 'like', '%' . $this->search . '%');
        });
    })
    ->latest()
    ->paginate(12);

        return view('livewire.ordermanager', [
            'orders' => $orders,
            'counts' => $this->getStatusCounts($user->tenant_id)
        ]);
    }

    private function getStatusCounts($tenantId)
    {
        return Order::where('tenant_id', $tenantId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}