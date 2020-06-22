@extends('user/layout/template')

@section('title', 'Ubah kata Sandi')

@section('title-page', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title">Ubah Kata Sandi</h4>
            {{-- <p class="card-category">Complete your profile</p> --}}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('ganti_password.store') }}">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="form-group">
                            <h6 class="text-dark font-weight-bold m-0">Kata Sandi</h6>
                            <input type="password" class="form-control" name="current_password">
                            @if ($errors->has('current_password'))
                                <span class="invalid">
                                    Password tidak cocok dengan password lama
                                </span>
                            @endif 
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <h6 class="text-dark font-weight-bold m-0">Kata Sandi Baru</h6>
                            <input type="password" class="form-control" name="new_password">
                        </div>
                        @if ($errors->has('new_password'))
                            <span class="invalid">
                                {{ $errors->first('new_password') }}
                            </span>
                        @endif 
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <h6 class="text-dark font-weight-bold m-0">Konfirmasi Kata Sandi Baru</h6>
                            <input type="password" class="form-control" name="new_confirm_password">
                        </div>
                        @if ($errors->has('new_confirm_password'))
                            <span class="invalid">
                                Konfirmasi password tidak cocok dengan password baru
                            </span>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <button type="submit" class="btn btn-primary pull-right">SIMPAN</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
    @include('sweetalert::alert')
@endpush