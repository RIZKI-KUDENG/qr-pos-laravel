<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if(!$tenant){
            abort(403, 'You are not authorized to access this page.');
        }

        // PERBAIKAN: Hapus ->get() yang pertama
        $categories = $tenant->categories()
            ->with(['products' => function ($query){
                $query->where('is_active', true);
            }])
            ->get(); // ->get() hanya dipanggil sekali di akhir

        return view('client.pos.index', compact('tenant', 'categories'));
    }

    public function store(Request $request)
{
    // Validasi dan logika simpan order
    // Anda bisa mengadaptasi logika dari MenuController::storeOrder
    
    // Contoh sederhana response sukses agar JS tidak error
    return response()->json([
        'status' => 'success', 
        'message' => 'Order berhasil dibuat'
    ]);
}

}