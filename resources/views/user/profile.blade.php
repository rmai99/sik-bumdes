@extends('user/layout/template')

@section('title', 'Profile')

@section('title-page', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        @role('company')
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Edit Profile</h4>
                {{-- <p class="card-category">Complete your profile</p> --}}
            </div>
            {{-- @php
                dd($errors);
            @endphp --}}
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    {{ method_field('PUT') }}
                    @csrf
                    <input type="hidden" name="id" value="{{ $data->id}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Perusahaan</label>
                                <input type="text" class="form-control" value="{{ $data->name }}" name="name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $data->user->email }}" required>
                                <input type="hidden" name="id_user" value="{{ $data->user->id }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">No telp</label>
                                <input type="number" class="form-control" value="{{ $data->phone_number }}" name="phone_number" required>
                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Alamat</label>
                                <input type="text" class="form-control" value="{{ $data->address }}" name="address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Status</label>
                                <input type="text" class="form-control" @if ($data->is_actived == 0)
                                value="Reguler"
                                @else
                                value="PRO"
                                @endif
                                disabled>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">SIMPAN PROFIL</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        @endrole
        @role('employee')
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Edit Profile</h4>
            </div>
            <div class="card-body">
                <form action="{{route('profile_karyawan.update')}}" method="post">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Company (disabled)</label>
                                <input type="text" class="form-control" disabled value="{{ $data->company->name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $data->name }}" name="name" required>
                                <input type="hidden" class="form-control" value="{{ $data->id }}" name="id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email</label>
                                <input type="text" class="form-control" value="{{ $data->user->email }}" name="email" required>
                                <input type="hidden" value="{{ $data->user->id }}" name="id_user">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Bisnis</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $data->business->business_name }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">SIMPAN PROFIL</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        @endrole
    </div>
</div>

@endsection
@push('js')
@include('sweetalert::alert')
@endpush
