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
}
