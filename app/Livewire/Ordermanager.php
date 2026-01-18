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


    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => '']
    ];


    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function setFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage(); 
    }
    

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

        if ($order && $order->status === 'pending') {
            $this->selectedOrderId = $order->id;
            $this->cashAmount = ''; 
            $this->changeAmount = -$order->total; 
            
            // Perintah Khusus ke Browser: "Buka Modal Sekarang!"
            $this->dispatch('open-payment-modal'); 
        }
    }


    public function updatedCashAmount()
    {
        $cash = (int) ($this->cashAmount ?? 0);
        $total = $this->selectedOrder ? $this->selectedOrder->total : 0;
        
        $this->changeAmount = $cash - $total;
    }


    public function setQuickCash($amount)
    {
        $this->cashAmount = $amount;
        $this->updatedCashAmount(); 
    }

    // Action: Submit Pembayaran
 public function submitPayment()
    {
        $order = $this->selectedOrder;
        if (!$order) return;

        if ((int)$this->cashAmount < $order->total) {
            return;
        }

        Payment::create([
            'order_id' => $order->id,
            'amount'   => $order->total,
            'method'   => 'cash',
            'status'   => 'paid',
        ]);

        $order->update(['status' => 'paid']);

        $this->closePaymentModal(); // Panggil fungsi close di atas
    }
    public function closePaymentModal()
    {
        $this->reset(['selectedOrderId', 'cashAmount', 'changeAmount']);
        
        // Perintah Khusus ke Browser: "Tutup Modal Sekarang!"
        $this->dispatch('close-payment-modal');
    }

    // Action: Update Status (Tolak / Selesai)
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
            ->paginate(6);

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