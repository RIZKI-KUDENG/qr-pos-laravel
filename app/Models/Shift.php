<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'tenant_id', 'user_id', 'start_time', 'end_time', 
        'start_cash', 'total_cash_sales', 'expected_cash', 
        'actual_cash', 'difference', 'status', 'notes'
    ];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
