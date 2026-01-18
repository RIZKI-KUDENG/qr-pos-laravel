<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreOrderAction
{
    public function execute(array $data, int $tenantId): Order
    {
        return DB::transaction(function () use ($data, $tenantId) {

            $order = Order::create([
                'tenant_id' => $tenantId,
                'status' => $data['status'] ?? 'paid',
                'order_number' => 'POS-' . strtoupper(Str::random(6)) . '-' . time(),
                'customer_name' => $data['customer_name'],
                'total' => $data['total_amount'],
            ]);

            foreach ($data['cart'] as $item) {
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
                'amount' => $data['cash_amount'],
                'change_amount' => $data['cash_amount'] - $data['total_amount'],
                'status' => 'paid',
                'transaction_id' => 'TRX-' . time() . '-' . $order->id,
            ]);

            return $order;
        });
    }
}
