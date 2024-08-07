@extends('layouts.user')

@section('main-content')
    <!-- Page Heading -->

    <nav aria-label="breadcrumb" class="navbar navbar-light bg-light mb-4 mt-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);">
        <ol class="breadcrumb my-auto p-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-fw fa-tachometer-alt"></i></a></li>
            <li class="breadcrumb-item" aria-current="page">Pembayaran</li>
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
                            <th scope="col">Total Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Action</th>
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
                                <td class="align-middle">
                                    Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="align-middle">
                                    {{ $transaction->qty }}
                                </td>
                                <td class="align-middle">
                                    @php $encryptID = Crypt::encrypt($transaction->id); @endphp
                                    <a href="{{ route('customer.paymentConfirmation', $encryptID) }}" class="btn btn-sm btn-outline-primary fw-bold rounded"><i class="fa fa-money-check-dollar"></i> Bayar</a>
                                    <form method="POST" action="{{ route('customer.destroy', $transaction->id) }}" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-flat show_confirm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i> Hapus</button>
                                    </form>
                                    {{-- <a href="" class="btn btn-sm btn-outline-danger fw-bold rounded"><i class="fa fa-trash"></i> Hapus</a> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center mt-2 mb-2">
                                    <span class="fst-italic">Tidak Ada Data Pembayaran</span><br><br>
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
