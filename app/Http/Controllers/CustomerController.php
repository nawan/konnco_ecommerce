<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customer.profile');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $decryptID = Crypt::decrypt($id);
        $product = Product::find($decryptID);
        return view('customer.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Auth::user();
        return view('customer.profile');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $data = $request->validate([
            'name' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
        ]);
        if ($request->file('foto')) {
            if ($request->oldFoto) {
                Storage::delete($request->oldFoto);
            }
            $data['foto'] = $request->file('foto')->store('customer');
        }

        $user->update($data);
        toastr()->success('Data Profil Anda Berhasil Diperbarui');
        return back();
    }

    public function change_password(Request $request)
    {
        $data = $request->validate([
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);
        if (Hash::check($request->oldPassword, auth()->user()->password)) {
            $user = User::find(auth()->user()->id);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            toastr()->success('Password Anda Berhasil Diubah');
        } else {
            toastr()->error('Password Lama Salah');
        }
        return back();
    }

    public function confirmation(string $id)
    {

        $decryptID = Crypt::decrypt($id);
        $product = Product::find($decryptID);

        return view('customer.confirmation', compact('product'));
    }

    public function checkout(Request $request)
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

        $encryptID = Crypt::encrypt($transaction->id);


        // // Set your Merchant Server Key
        // \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = false;
        // // Set sanitization on (default)
        // \Midtrans\Config::$isSanitized = true;
        // // Set 3DS transaction for credit card to true
        // \Midtrans\Config::$is3ds = true;

        // $params = array(
        //     'transaction_details' => array(
        //         'order_id' => $transaction->id,
        //         'gross_amount' => $transaction->total_harga,
        //     ),
        //     'customer_details' => array(
        //         'name' => Auth::user()->name,
        //         'email' => Auth::user()->email,
        //         'phone' => Auth::user()->no_hp,
        //         'alamat' => Auth::user()->alamat,
        //     ),
        // );

        // $snapToken = \Midtrans\Snap::getSnapToken($params);
        //dd($snapToken);
        toastr()->success('Produk Berhasil Dilakukan Checkout');
        // return redirect()->back()->with(compact('snapToken'));
        return redirect()->route('customer.paymentConfirmation', $encryptID);

        //return view('customer.checkout', compact('product', 'snapToken'));
    }

    public function paymentConfirmation(String $id)
    {
        $decryptID = Crypt::decrypt($id);
        $transaction = Transaction::find($decryptID);

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

        return view('customer.checkout', compact('transaction', 'snapToken'));
    }


    public function paymentList()
    {
        $transactions = Transaction::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'UNPAID')
            ->latest()
            ->paginate(5);

        return view('customer.payment', compact('transactions'));
    }

    public function pay(Request $request)
    {

        $serverKey = config('midtrans.server_key');
        $hash = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hash == $request->signature_key) {
            if ($request->transaction_status == 'capture') {
                $transaction = Transaction::find($request->order_id);
                $transaction->update(['status' => 'PAID']);
            }
        }
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', '=', Auth::user()->id)
            ->latest()
            ->paginate(5);

        return view('customer.history', compact('transactions'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::find($id);
        $product = Product::find($transaction->product_id);
        $product->stock = $product->stock + $transaction->qty;
        $product->save();

        $transaction->delete();
        toastr()->success('Data Pembayaran Berhasil Dihapus');
        return back();
    }
}
