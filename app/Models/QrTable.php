<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    protected $fillable = [
        'table_number',
        'qr_code_url'
    ];
    protected static function booted(): void
    {
        static::creating(function ($qrTable) {
            if (Auth::check()) {
                $qrTable->tenant_id = Auth::user()->tenant_id;
            }
        });
        static::created(function ($qrTable) {
            $tenantSlug = $qrTable->tenant->slug;
            
            $url = url("/menu/{$tenantSlug}/{$qrTable->id}");
            $qrTable->qr_code_url = $url;
            $qrTable->saveQuietly();
        });
    }
}
