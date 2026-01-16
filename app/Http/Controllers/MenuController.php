<?php
namespace App\Http\Controllers;
use App\Models\Tenant;
use App\Models\QrTable;
use App\Http\Controllers\Controller;

class MenuController extends Controller{


    public function index(Tenant $tenant){
        $categories = $tenant->categories()->get()->with(['products' => function ($query){
            $query->where('is_active', true);
        }])->get();
        return view('client.menu.index', compact('tenant', 'categories'));
    }
    public function table(Tenant $tenant, QrTable $qrTable)
    {
        // Logika sama, tapi kita kirim data meja juga ke view
        // Pastikan QrTable memang milik Tenant ini untuk keamanan
        if ($qrTable->tenant_id !== $tenant->id) {
            abort(404);
        }

        $categories = $tenant->categories()
            ->with(['products' => fn($q) => $q->where('is_active', true)])
            ->get();

        return view('client.menu.index', compact('tenant', 'categories', 'qrTable'));
    }
}