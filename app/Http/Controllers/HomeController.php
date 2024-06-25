<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware(['role_or_permission:admin|show_dashboard']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //dd(auth()->user()->getRoleNames());
        $search = $request->search;
        $products = Product::where('status', '=', 'ACTIVE')
            ->latest()
            ->paginate(4);
        // $cart = Transaction::where('user_id', '=', auth()->user()->id)
        //     ->get();
        // $myCart = $cart->count();

        if (auth()->user()->is_admin == 'YES') {
            $users = User::where('is_admin', '=', 'NO')->count();
            $products = Product::count();
            $payment = Transaction::where('status', '=', 'PAID')
                ->latest()->get();
            $totalPendapatan = $payment->sum('total_harga');
            $pending = Transaction::where('status', '=', 'UNPAID')
                ->latest()->get();
            $totalPending = $pending->sum('total_harga');

            return view('admin.dashboard', compact('users', 'products', 'totalPendapatan', 'totalPending'));
        }
        return view('customer.index', compact('products'));
        //return abort(403);

    }

    public function dashboard_user()
    {
    }
}
