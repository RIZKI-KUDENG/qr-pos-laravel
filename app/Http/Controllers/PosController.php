<?php
namespace App\Http\Controllers;
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

        $categories = $tenant->categories()->get()->with(['products' => function ($query){
            $query->where('is_active', true);
        }])->get();
        return view('client.pos.index', compact('tenant', 'categories'));
    }

}