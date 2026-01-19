<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProductManager extends Component
{

    use WithPagination;
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        // dd(get_class($product)); 
        if ($product->tenant_id == Auth::user()->tenant_id) {
            $product->is_active = !$product->is_active;
            $product->save();
        }
    }

    public function render()
    {
        return view('livewire.product-manager', [
            'products' => Product::where('tenant_id', Auth::user()->tenant_id)
                ->with('category')
                ->orderBy('category_id')
                ->paginate(10)
        ]);
    }
}
