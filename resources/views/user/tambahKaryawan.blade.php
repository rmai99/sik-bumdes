@extends('user/layout/template')

@section('title', 'Profile')

@section('title-page', 'Profile')

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
                                    <input class="form-control" name="name" id="input-name" type="text"
                                        placeholder="Name" value="" required="true" aria-required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="email" id="input-email" type="email"
                                        placeholder="Email" value="" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password">Password</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" input type="password" name="password"
                                        id="input-password" placeholder="Password" value="" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password-confirmation">Confirm
                                Password</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="password_confirmation"
                                        id="input-password-confirmation" type="password" placeholder="Confirm Password"
                                        value="" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password-confirmation">Akses Bisnis</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <select class="form-control" name="id_business">
                                        <option value="0" selected disabled>Bisnis</option>
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