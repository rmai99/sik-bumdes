@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card">
                    <div class="card-body register-card-body">
                        <div class="register-logo">
                            <a href="">SIK<b>BUMDes</b></a>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <h2>Daftar</h2>
                            <p class="mb-0 font-14">Nama Perusahaan</p>
                            <div class="input-group mb-2 ">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name"
                                    placeholder="Nama Perusahaan" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 font-14">Alamat Perusahaan</p>
                            <div class="input-group mb-2">
                                <input id="address" type="text"
                                    class="form-control @error('address') is-invalid @enderror" name="address"
                                    value="{{ old('address') }}" required autocomplete="address"
                                    placeholder="Alamat Perusahaan">

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 font-14">Nomor Telepon</p>
                            <div class="input-group mb-2">
                                <input id="phoneNumber" type="text"
                                    class="form-control @error('phoneNumber') is-invalid @enderror" name="phoneNumber"
                                    value="{{ old('phoneNumber') }}" required autocomplete="phoneNumber"
                                    placeholder="Nomor Telepon">

                                @error('phoneNumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 font-14">Email</p>
                            <div class="input-group mb-2">
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
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password" placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 font-14">Confirm Password</p>
                            <div class="input-group mb-2">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-register-login mb-3">DAFTAR</button>
                            </div>
                        </form>

                        <a href="{{ route('login') }}" class="text-center">Sudah Punya Akun</a>
                    </div>
                    <!-- /.form-box -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
