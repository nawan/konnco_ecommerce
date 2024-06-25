@push('script')
<script>
    $(document).ready(function() {
    $('#status').change(function() {
        if ($(this).val() === 'ACTIVE') {
            $("input[name='stock']").prop('readonly',true);
            document.querySelector('input[name="stock"]').value = '0'
        }else{
            $("input[name='stock']").prop('readonly',false);
        };     
    }); 
});
</script>
<script>
    $(document).ready(function() {
    $('#status').change(function() {
        if ($(this).val() == 'INACTIVE') {
            $("input[name='stock']").prop('readonly',true);
            document.querySelector('input[name="stock"]').value = '0'
        }else{
            $("input[name='stock']").prop('readonly',false);
        };     
    }); 
});
</script>
@endpush
@extends('layouts.admin')

@section('main-content')
@if (session('success'))
<div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger border-left-danger" role="alert">
    <ul class="pl-4 my-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <!-- Page Heading -->

    <nav aria-label="breadcrumb" class="navbar navbar-light bg-light mb-4 mt-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);">
        <ol class="breadcrumb my-auto p-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-fw fa-tachometer-alt"></i></a></li>
            <li class="breadcrumb-item" aria-current="page">Produk</li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('product.create') }}">Tambah Produk</a></li>
        </ol>
    </nav>
    
    <div class="card mt-10 mb-5 ml-3">
        <div class="card-header text-center fw-bold text-uppercase mb-10 bg-success text-white">
            Tambah Data Produk
        </div>
        <div class="card-group">
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf()
                    <div class="row">
                        <div class="form-group mb-3 card-text col-md-12">
                            <label for="nama" class="form-label">Nama Bahan</label>
                            <input type="text" class="form-control count-chars text-uppercase @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" maxlength="50" data-max-chars="50">
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('nama')
                            <span class="invalid-feedback">Nama Bahan Masih Kosong</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 mb-3">
                            <label for="harga" class="form-label">Harga Bahan</label>
                            <div class="input-group">
                                <div class="input-group-text">Rp</div>
                                <input type="text" class="form-control count-chars @error('harga') is-invalid @enderror" name="harga" id="currency" value="{{ old('harga') }}" maxlength="15" data-max-chars="15">
                            </div>
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('harga')
                            <span class="invalid-feedback">Harga Masih Kosong</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="stock" class="form-label">Jumlah Stok</label>
                            <input type="number" class="form-control count-chars @error('stock') is-invalid @enderror" name="stock" id="stock" value="{{ old('jml_stok') }}" maxlength="10" data-max-chars="15">
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('stock')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="status" class="form-label">Status Bahan</label>
                            <br>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="">---Silahkan Pilih---</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="INACTIVE">INACTIVE</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Bahan</label>
                        <img src="" class="mb-3 img-preview img-fluid col-sm-5" alt="">
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" onchange="previewImage()">
                        @error('foto') 
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3 card-text">
                        <label for="deskripsi" class="form-label">Deskripsi Bahan</label>
                        <textarea rows="20" name="deskripsi" id="deskripsi-editor" class="form-control count-chars @error('deskripsi') @enderror" maxlength="600" data-max-chars="600">{{ old('deskripsi') }}</textarea>
                        <div class="fw-light text-muted justify-content-end d-flex"></div>
                        @error('deskripsi')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                        <a href="{{ route('product.index') }}" class="btn btn-danger">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
