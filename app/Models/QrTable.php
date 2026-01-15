<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrTable extends Model
{
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
