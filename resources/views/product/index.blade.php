@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->

    <nav aria-label="breadcrumb" class="navbar navbar-light bg-light mb-4 mt-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);">
        <ol class="breadcrumb my-auto p-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-fw fa-tachometer-alt"></i></a></li>
            <li class="breadcrumb-item" aria-current="page">Produk</li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('product.index') }}">Data Produk</a></li>
        </ol>
    </nav>
    
    <section class="content mb-10 ml-3">
        <div class="card mt-10 mb-5">
            <div class="card-header text-center fw-bold text-uppercase mb-10 bg-dark text-white">
                Data Produk
            </div>
            <div class="card-body">
                <div class="d-flex mt-4 mb-3">
                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus-square"></i> Tambah Data Produk</a>
                </div>
                <div class="justify-content-center">
                    <form action="{{ route('product.index') }}" method="GET">
                        <div class="row">
                            <div class="col">
                                <div class="input-group mb-3">
                                    <input type="text" value="{{ Request::input('search') }}" class="form-control" placeholder="Nama Produk..." name="search">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="table table-bordered table-hover text-center align-middle stripe" id="data-product">
                    <thead class="thead thead-light bg-secondary text-white table-bordered">
                        <tr class="text-center">
                            <th scope="col">No</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="text-center">
                                <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                <td class="align-middle text-capitalize fw-bold">
                                    {{ $product->nama }}
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->foto) }}" width="80" alt="">
                                </td>
                                <td class="align-middle">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </td>
                                <td class="align-middle">
                                    {{ $product->stock }}
                                </td>
                                <td class="align-middle">
                                    @if($product->status == 'ACTIVE')
                                        <span class="badge bg-success fw-bold rounded">ACTIVE</span>
                                    @else
                                        <span class="badge bg-danger fw-bold rounded">INACTIVE</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @php $encryptID = Crypt::encrypt($product->id); @endphp
                                        <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger btn-flat show_confirm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('product.edit',$encryptID) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Info" data-bs-toggle="modal" data-bs-target="#detail{{ $product->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                </td>

                                <!-- Modal -->
                                <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="detail{{ $product->id }}" tabindex="-1" aria-labelledby="detail{{ $product->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="card mt-10">
                                                <div class="card-header text-center fw-bold text-uppercase mb-10 bg-info text-white">
                                                        Detail Data Produk
                                                </div>
                                                <div class="card-group bg-light mt-10">
                                                    <div class="card">
                                                        <div class="card-header text-center text-uppercase fw-bold mb-3">
                                                            Foto Produk
                                                        </div>
                                                        <div class="card-body text-center" style="400px">
                                                            <p class="card-title text-capitalize fw-bold mb-3 h4">
                                                                <img src="{{ asset('storage/' . $product->foto) }}" width="500" alt="">
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header text-center text-uppercase fw-bold mb-3">
                                                            Data Produk
                                                        </div>
                                                        <div class="cad-body text-center mb-3" style="400px">
                                                            <p class="card-text fw-bold m-0">Nama Produk</p>
                                                            <p class="fst-italic text-capitalize mb-2">{{ $product->nama }}</p>
                                                            <p class="card-text fw-bold m-0">Harga</p>
                                                            <p class="fst-italic text-uppercase mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                                            <p class="card-text fw-bold m-0">Stok</p>
                                                            <p class="fst-italic text-uppercase mb-2">{{ $product->stock  }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center mt-2 mb-2">
                                    <span class="fst-italic">Data Tidak Ditemukan</span><br><br>
                                    <a href="{{ route('product.index') }}" class="btn btn-info btn-sm"><i class="fa fa-recycle"></i> Refresh</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </section>

@endsection
