@extends('user/layout/template')

@section('title', 'Karyawan')

@section('title-page', 'Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('karyawan.store')}}" class="form-horizontal">
                @csrf
                <div class="card ">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Tambah Karyawan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('karyawan.index')}}"
                                    class="btn btn-sm btn-primary">Daftar Karyawan</a>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="name" type="text" value="{{ old('name') }}" required="true" aria-required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password">Password</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="password" id="input-password" type="password" required />
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif 
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password-confirmation">Akses Bisnis</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <select class="form-control" name="id_business" required>
                                        <option value="" selected>Bisnis</option>
                                        @foreach ($business as $item)
                                            <option value="{{ $item->id }}">{{ $item->business_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ml-auto mr-auto">
                        <button type="submit" class="btn btn-primary">Tambah Karyawan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection