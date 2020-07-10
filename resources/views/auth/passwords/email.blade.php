@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="text-center mt-3 mb-1">
                        <h3> SIK <strong> BUMDes </strong> </h3>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <h4 class="font-weight-bold">Daftar</h2>
                        <label for="email">{{ __('E-Mail Address') }}</label>
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
                                <strong>Kami tidak dapat menemukan pengguna dengan alamat email {{ old('email') }}.</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row mb-0 justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-register">
                                    Kirim Tautan Reset Kata Sandi
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('login') }}"
                                class="text-center text-dark"><strong>Masuk!</strong></a>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('register') }}"
                                class="text-center text-dark text-right"><strong>Daftar!</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
