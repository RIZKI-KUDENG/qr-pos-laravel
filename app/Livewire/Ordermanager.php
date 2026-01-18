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
    public $cashAmount = ''; 
    public $changeAmount = 0;

    protected $queryString = ['statusFilter', 'search'];

    public function getSelectedOrderProperty()
    {
        if (!$this->selectedOrderId) return null;

        return Order::with('qrTable')
            ->where('tenant_id', Auth::user()->tenant_id)
            ->find($this->selectedOrderId);
    }

    public function openPaymentModal($orderId)
    {
        $order = Order::where('tenant_id', Auth::user()->tenant_id)->find($orderId);

        if (!$order || $order->status !== 'pending') return;

        $this->selectedOrderId = $order->id;
        $this->cashAmount = ''; 
        $this->changeAmount = -$order->total; 
        $this->isPaymentModalOpen = true;
    }


    public function updatedCashAmount($value)
    {

        if (!$this->selectedOrder) return;


        $cash = empty($value) ? 0 : (int) $value;
        
        $this->changeAmount = $cash - $this->selectedOrder->total;
    }

    public function submitPayment()
    {
        $order = $this->selectedOrder;

        if (!$order) return;


        if ((int)$this->cashAmount < $order->total) {
            $this->addError('cashAmount', 'Uang kurang');
            return;
        }


        Payment::create([
            'order_id' => $order->id,
            'amount'   => $order->total,
            'method'   => 'cash',
            'status'   => 'paid',
        ]);


        $order->update(['status' => 'paid']);


        $this->reset([
            'isPaymentModalOpen',
            'selectedOrderId',
            'cashAmount',
            'changeAmount'
        ]);
        
    }

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
            ->paginate(3);

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