@extends('layouts.user')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Konfirmasi Pembelian</h1>

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

    <div class="card mt-10 mb-5">
        <div class="card-group bg-light mt-10">
            <div class="card-body text-center col-md-6">
                <div class="card-body bg-white p-5" style="width:100%;max-heigth:600px">
                    <img src="{{ asset('storage/' . $product->foto) }}" class="rounded img-thumbnail" width="500" data-bs-toggle="modal" data-bs-target="#detail-foto" style="cursor: pointer">
                </div>
            </div>
    
            {{-- modal view show image --}}
            <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="detail-foto" tabindex="-1" aria-labelledby="Foto Bahan {{ $product->nama }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-transparent" style="border: none">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close-white" data-bs-dismiss="modal"><i class="fa" style="font-size: 2rem;">&#xf00d;</i></button>
                        </div>
                        <div class="modal-body d-flex justify-content-center">
                            <img src="{{ asset('storage/' . $product->foto) }}" style="width:100%;max-width:600px">
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="card-body col-md-6">
                <div class="card-body text-left bg-white p-3" style="width:100%;max-heigth:600px">
                    <p class="card-text fw-bold m-0">Nama</p>
                    <p class="fst-italic text-capitalize mb-2">{{ $product->nama }}</p>
                    <p class="card-text fw-bold m-0">Harga</p>
                    <p class="fst-italic text-capitalize mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="card-text fw-bold m-0">Jumlah Stock</p>
                    <p class="fst-italic text-capitalize mb-2">{{ $product->stock }}</p>
                    <p class="card-text fw-bold m-0">Status</p>
                    <p class="fst-italic text-uppercase mb-2">
                        @if($product->status == 'ACTIVE')
                        <span class="badge bg-success text-uppercase">READY STOCK</span>
                        @else
                        <span class="badge bg-danger text-uppercase">OUT OF STOCK</span>
                        @endif
                    </p>
                    <form action="{{ route('customer.checkout') }}" method="POST" enctype="multipart/form-data">
                        @csrf()
                        <div class="row" style="display:none">
                            <input type="text" class="form-control text-capitalize count-chars @error('product_id') is-invalid @enderror" id="product_id" name="product_id" value="{{ old('product_id', $product->id) }}" maxlength="20" data-max-chars="20" hidden>
                        </div>
                        <div class="row">
                            <label for="qty" class="form-label fw-bold">Jumlah Pesanan</label>
                            <input type="number" class="form-control ml-2 count-chars @error('qty') is-invalid @enderror" style="width: 200px" name="qty" id="qty" maxlength="10" data-max-chars="15">
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('qty')
                            <span class="invalid-feedback">Jumlah Beli Belum Terisi</span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end mt-3 gap-2">
                            <a href="{{ route('home') }}" class="btn btn-danger fw-bold">BATAL</a>
                            <button type="submit" class="btn btn-outline-success fw-bold">CHECKOUT</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body col-md-12">
                <div class="card-header text-center text-uppercase fw-bold bg-secondary text-white">
                    Deskripsi
                </div>
                <div class="card-body bg-white p-3" style="width:100%; max-heigth:500px">
                    <p class="fst-italic text-capitalize mb-2">{!! html_entity_decode($product->deskripsi, ENT_QUOTES, 'UTF-8' ) !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

