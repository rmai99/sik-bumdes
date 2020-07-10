@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="text-center mt-3 mb-1">
                    <h3> SIK <strong> BUMDes </strong> </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('login') }}" method="POST">
                        <h4 class="font-weight-bold">Masuk</h2>
                        @csrf
                        <p class="mb-0">Email</p>
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="material-icons" style="font-size:18px">
                                        email
                                    </i>
                                </div>
                            </div>
                            <input id="email" type="email" placeholder="Email "
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>Email atau password salah</strong>
                            </span>
                            @enderror
                        </div>
                        <p class="mb-0">Kata Sandi</p>
                        <div class="input-group mb-0">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="material-icons" style="font-size:18px">
                                        lock
                                    </i>
                                </div>
                            </div>
                            <input id="password" placeholder="Password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            {{-- <div class="col-3">
                                <input class="form-check-input " type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div> --}}
                            <div class="col-12 d-flex justify-content-end p-0">
                                @if (Route::has('password.request'))
                                <a class="btn btn-link text-dark" href="{{ route('password.request') }}">
                                    {{ __('Lupa Kata Sandi?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-register mb-3">MASUK</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="{{ route('register') }}"
                                    class="text-center btn btn-make-user">Belum Punya Akun? <strong>Daftar!</strong></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
