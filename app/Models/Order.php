<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function qrTable()
    {
        return $this->belongsTo(QrTable::class);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    protected $fillable = [
        'tenant_id',
        'qr_table_id',
        'customer_name',
        'status',
        'total',
        'order_number',
    ];
}
