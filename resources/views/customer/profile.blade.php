@extends('layouts.user')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Profile') }}</h1>

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

    <div class="row mt-10 mb-5">
        <div class="col-md-4 col-xl-7 mb-4">
            <div class="card">
            <div class="card-header text-center fw-bold text-uppercase mb-10 bg-warning text-white">
                Edit Data Profil
            </div>
            <div class="card-group">
                <div class="card-body">
                <form action="{{ route('customer.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf()
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control text-uppercase count-chars @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" maxlength="20" data-max-chars="20">
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <br>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">
                                <option {{ (Auth::user()->jenis_kelamin == 'PRIA') ? 'selected' : '' }} value="PRIA">PRIA</option>
                                <option {{ (Auth::user()->jenis_kelamin == 'WANITA') ? 'selected' : '' }} value="WANITA">WANITA</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No Kontak/HP</label>
                            <input type="number" class="form-control count-chars @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}"maxlength="13" data-max-chars="13">
                            <div class="fw-light text-muted justify-content-end d-flex"></div>
                            @error('no_hp')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" readonly>
                            @error('email')
                            <span class="invalid-feedback">
                            {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="hidden" name="oldFoto" value="{{ Auth::user()->foto }}">
                            @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block" alt=""> 
                            @else
                            <img src="" class="img-preview img-fluid mb-3 col-sm-5" alt="">
                            @endif
                            <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" onchange="previewImage()">
                            @error('foto') 
                            <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea rows="5" name="alamat" id="alamat" class="form-control text-capitalize count-chars @error('alamat') @enderror" maxlength="100" data-max-chars="100">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                        <div class="fw-light text-muted justify-content-end d-flex"></div>
                        @error('alamat')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                        @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                </form>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-5 mb-4">
            <div class="card">
            <div class="card-header text-center fw-bold text-uppercase mb-10 bg-warning text-white">
            Ganti Password
            </div>
            <div class="card-group">
                <div class="card-body">
                    <form action="{{ route('customer.change_password') }}" method="POST" enctype="multipart/form-data">
                        @csrf()
                            <div class="mb-3">
                                <label for="oldPassword" class="form-label">Password Lama</label>
                                <input type="password" data-toggle="password" required class="form-control password @error('oldPassword') is-invalid @enderror" name="oldPassword" id="oldPassword" >
                                @error('oldPassword')
                                <span class="invalid-feedback">
                                {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" data-toggle="password" required class="form-control password @error('password') is-invalid @enderror" name="password" id="password" >
                                @error('password')
                                <span class="invalid-feedback">
                                {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" data-toggle="password" required class="form-control password @error('password') is-invalid @enderror" name="password_confirmation" id="password_confirmation" >
                                @error('password')
                                <span class="invalid-feedback">
                                {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('script')
{{-- jquery jscript --}}
<script type="text/javascript" src="{{ URL::asset('jquery/jquery-3.6.4.slim.js') }}"></script>
{{-- <script type="text/javascript" src="{{ URL::asset('js/form-validation.js') }}"></script> --}}
<script type="text/javascript" src="{{ URL::asset('js/image-preview.js') }}"></script>
@endpush

@endsection
