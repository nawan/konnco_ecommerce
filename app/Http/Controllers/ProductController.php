<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:admin|view_dashboard']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $products = Product::where('nama', 'like', '%' . $search . '%')
            ->latest()
            ->paginate(10);

        return view('product.index', compact('products'));
    }

    public function transaction()
    {
        $transactions = Transaction::latest()->paginate(10);

        return view('admin.transaction', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'stock' => 'required',
            'harga' => 'required',
            'foto' => 'required|image',
            'deskripsi' => 'required',
        ]);

        $data['harga'] = Str::replace('.', '', $request->harga);

        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('product');
        }

        Product::create($data);
        toastr()->success('Data Produk Berhasil Ditambahkan');
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('product.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $decryptID = Crypt::decrypt($id);
        $product = Product::find($decryptID);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'stock' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        $data['harga'] = Str::replace('.', '', $request->harga);

        if ($request->file('foto')) {
            if ($request->oldFoto) {
                Storage::delete($request->oldFoto);
            }
            $data['foto'] = $request->file('foto')->store('product');
        }

        $product->update($data);
        toastr()->success('Data Produk Berhasil Diperbarui');
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if ($product->foto) {
            Storage::delete($product->foto);
        }

        $product->delete();
        toastr()->success('Data Produk Telah Dihapus');
        return redirect()->route('product.index');
    }
}
