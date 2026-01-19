<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Ordermanager extends Component
{
    use WithPagination;
    public $statusFilter = 'all';
    public $search = '';
    public $isPaymentModalOpen = false;
    public $selectedOrderId = null;
    public  $cashAmount = '';
    public $selectedOrder = null;
    public $changeAmount = 0;

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => '']
    ];


    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }


    public function setFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    public function updatedCashAmount()
    {
        $this->updateChangeAmount();
    }
    public function openPaymentModal($orderId)
    {
        $order = Order::find($orderId);
        if (!$order || $order->status !== 'pending') return;
        $this->selectedOrderId = $order->id;
        $this->selectedOrder = $order;
        $this->cashAmount = '';
        $this->changeAmount = $order->total;
        $this->updateChangeAmount();
        $this->isPaymentModalOpen = true;
    }
    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
    }

    public function setQuickCash($amount)
    {
        $this->cashAmount = $amount;
        $this->updateChangeAmount();
    }
    public function updateChangeAmount()
    {
        $cash = (int) ($this->cashAmount == '' ? 0 : $this->cashAmount);
        $total = $this->selectedOrder ? $this->selectedOrder->total : 0;

        $this->changeAmount = $cash - $total;
    }


    public function submitPayment()
    {
        $order = Order::findorFail($this->selectedOrderId);

        if (!$order) return;
        $order->status = 'paid';
        $order->save();
        $url = route('pos.print', ['orderNumber' => $order->order_number]);
        $this->dispatch('open-print-window', url: $url);

        $this->closePaymentModal();
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
            ->whereDate('created_at', today())
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
            ->paginate(6);

        return view('livewire.ordermanager', [
            'orders' => $orders,
            'counts' => $this->getStatusCounts($user->tenant_id)
        ]);
    }

    private function getStatusCounts($tenantId)
    {
        return Order::where('tenant_id', $tenantId)
            ->whereDate('created_at', today())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
