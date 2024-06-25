@extends('layouts.user')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Produk Kami</h1>

    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="container mt-5 mb-5">
        <div class="card bg-white">
            <div class="card-body">
                <div class="row">
                    @forelse($products as $product)
                    @php $encryptID = Crypt::encrypt($product->id); @endphp
                    <div class="col-md-3 m-auto d-flex justify-content-between">
                        <div class="card card-hover shadow-lg mb-2 mt-2" style="width: 18rem;">
                            {{-- <img src="{{ asset('storage/' .$product->foto) }}" class="card-img-top" style="height: 200px; width:auto;"> --}}
                            <a href="{{ route('customer.show',$encryptID) }}" class="text-decoration-none text-muted">
                                <div class="card card-img-top">
                                    <img src="{{ asset('storage/' .$product->foto) }}" class="card-img-top" style="height: 200px; width:auto;">
                                </div>
                                <div class="card-body">
                                    <div class="card-title fw-bold text-uppercase mt-2">
                                        {{ $product->nama }}
                                        <?php
                                        if ($product['status'] == 'INACTIVE') {
                                        ?>
                                            <span class="badge bg-danger fw-bold text-uppercase">
                                                habis
                                            </span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge bg-info fw-bold text-uppercase">
                                                tersedia
                                            </span>
                                        <?php
                                        }
                                        ?>
    
                                    </div>
                                    <p class="card-text">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                    <p class="card-text">
                                        {!! Str::limit($product->deskripsi, 50, '...') !!}
                                    </p>
                                    <p class="card-text">
                                        Jumlah Stok : <span class="badge bg-primary fw-bold">{{ $product->stock }}</span>
                                    </p>
                                </div>
                            </a>
                            <div class="card-footer d-flex justify-content-end">
                                @if($product->status == 'ACTIVE')
                                    {{-- <a href="" class="btn btn-success btn-sm m-1" data-toggle="tooltip" data-placement="top" title="Tambahkan Keranjang"><i class="fa fa-plus"></i> Keranjang</a> --}}
                                    <a href="{{ route('customer.confirmation',$encryptID) }}" class="btn btn-outline-success fw-bold m-1" data-toggle="tooltip" data-placement="top" title="Checkout">CHECKOUT</a>
                                @else
                                    <a href="{{ route('customer.confirmation',$encryptID) }}" class="btn btn-outline-secondary fw-bold m-1 disabled" data-toggle="tooltip" data-placement="top" title="Checkout">CHECKOUT</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-md-3 m-auto d-flex justify-content-between text-center fw-bold text-uppercase">
                        Produk Belum Tersedia
                    </div>
                    @endforelse
                </div>
            </div>
            {{-- <div class="d-flex justify-content-between">
                {{ $products->links() }}
            </div> --}}
            @if($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
