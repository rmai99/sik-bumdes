@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-7 col-lg-5">
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mt-3 mb-1">
                            <h3> SIK <strong> BUMDes </strong> </h3>
                        </div>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <h4 class="font-weight-bold">Daftar</h2>
                            <p class="mb-0 font-14">Nama Perusahaan</p>
                            <div class="input-group mb-2 ">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class=" material-icons" style="font-size:18px">
                                            business
                                        </i>
                                    </div>
                                </div>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name"
                                    placeholder="Nama Perusahaan" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <p class="mb-0 font-14">Alamat Perusahaan</p>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class=" material-icons" style="font-size:18px">
                                            home
                                        </i>
                                    </div>
                                </div>
                                <input id="address" type="text"
                                    class="form-control @error('address') is-invalid @enderror" name="address"
                                    value="{{ old('address') }}" required autocomplete="address"
                                    placeholder="Alamat Perusahaan">
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <p class="mb-0 font-14">Nomor HP</p>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class=" material-icons" style="font-size:18px">
                                            call
                                        </i>
                                    </div>
                                </div>
                                <input id="phone_number" type="text"
                                    class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
                                    value="{{ old('phone_number') }}" required autocomplete="phone_number"
                                    placeholder="Nomor Telepon">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <p class="mb-0 font-14">Email</p>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class=" material-icons" style="font-size:18px">
                                            email
                                        </i>
                                    </div>
                                </div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="input-group mb-2">
                                <input id="role" type="hidden" name="role" value="owner">
                            </div>
                            <p class="mb-0 font-14">Password</p>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="material-icons" style="font-size:18px">
                                            lock
                                        </i>
                                    </div>
                                </div>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password" placeholder="Password">
                                    @if ($errors->has('password'))
                                        <span class="invalid">
                                            <strong>{{ $errors->first('password') }}<strong>
                                        </span>
                                    @endif 
                                </div>
                            <p class="mb-0 font-14">Konfirmasi Password</p>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="material-icons" style="font-size:18px">
                                            lock
                                        </i>
                                    </div>
                                </div>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm Password">
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-register mb-3">DAFTAR</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="{{ route('login') }}"
                                    class="text-center text-dark">Sudah Punya Akun? <strong>Masuk!</strong></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.form-box -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
