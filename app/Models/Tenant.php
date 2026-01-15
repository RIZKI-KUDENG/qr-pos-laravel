<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
   public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function qrTables()
    {
        return $this->hasMany(QrTable::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    protected $fillable = ['name', 'slug', 'status'];
}
