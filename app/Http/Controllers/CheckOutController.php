<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::find($request->product_id);
        $encryptID = Crypt::encrypt($product->id);

        if ($request->qty == "") {
            toastr()->warning('Mohon Maaf, Jumlah Pesanan Belum Terisi');
            return redirect()->route('customer.confirmation', $encryptID);
        } elseif ($request->qty == 0) {
            toastr()->warning('Mohon Maaf, Jumlah Pesanan Produk Tidak Boleh 0');
            return redirect()->route('customer.confirmation', $encryptID);
        } elseif ($product->stock < $request->qty) {
            toastr()->warning('Mohon Maaf, Jumlah Pesanan Melebihi Stock');
            return redirect()->route('customer.confirmation', $encryptID);
        } elseif ($product->stock > $request->qty) {
            $product->stock = $product->stock - $request->qty;
            $product->save();

            $data['user_id'] = auth()->user()->id;
            $data['product_id'] = $product->id;
            $data['qty'] = $request->qty;
            $data['total_harga'] = $product->harga * $request->qty;
            $data['status'] = "UNPAID";
            $transaction = Transaction::create($data);
        } elseif ($product->stock == $request->qty) {
            $product->stock = 0;
            $product->status = 'INACTIVE';
            $product->save();

            $data['user_id'] = auth()->user()->id;
            $data['product_id'] = $product->id;
            $data['qty'] = $request->qty;
            $data['total_harga'] = $product->harga * $request->qty;
            $data['status'] = "UNPAID";
            $transaction = Transaction::create($data);
        }


        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $transaction->id,
                'gross_amount' => $transaction->total_harga,
            ),
            'customer_details' => array(
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->no_hp,
                'alamat' => Auth::user()->alamat,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        //dd($snapToken);
        toastr()->success('Produk Berhasil Dilakukan Checkout');
        return redirect()->back()->with(compact('snapToken'));
        // return redirect()->back()->with(compact('user','identity','pallet','categories','warehouses',.............));

        // return view('customer.confirmation', $encryptID);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
