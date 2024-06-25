@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->

    <nav aria-label="breadcrumb" class="navbar navbar-light bg-light mb-4 mt-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);">
        <ol class="breadcrumb my-auto p-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-fw fa-tachometer-alt"></i></a></li>
            <li class="breadcrumb-item" aria-current="page">Data Transaksi</li>
        </ol>
    </nav>
    
    <section class="content mb-10 ml-3">
        <div class="card mt-10 mb-5">
            <div class="card-header text-center fw-bold text-uppercase mb-10 bg-dark text-white">
                Data Pembayaran
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover text-center align-middle stripe" id="data-product">
                    <thead class="thead thead-light bg-secondary text-white table-bordered">
                        <tr class="text-center">
                            <th scope="col">No</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Pembeli</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="text-center">
                                <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                <td class="align-middle text-capitalize fw-bold">
                                    {{ $transaction->product->nama }}
                                </td>
                                <td>
                                    <img src="{{ asset('storage/' . $transaction->product->foto) }}" width="80" alt="">
                                </td>
                                <td class="align-middle text-capitalize fw-bold">
                                    {{ $transaction->user->name }}
                                </td>
                                <td class="align-middle">
                                    Rp {{ number_format($transaction->product->harga, 0, ',', '.') }}
                                </td>
                                <td class="align-middle">
                                    @if($transaction->status == 'PAID')
                                        <span class="badge bg-success fw-bold rounded">BAYAR</span>
                                    @else
                                        <span class="badge bg-danger fw-bold rounded">BELUM BAYAR</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center mt-2 mb-2">
                                    <span class="fst-italic">Tidak Ada Data</span><br><br>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
            </div>
        </div>
    </section>

@endsection
