@extends('user/layout/template')

@section('title', 'Profile')

@section('title-page', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        @role('owner')
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Edit Profile</h4>
                {{-- <p class="card-category">Complete your profile</p> --}}
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Company</label>
                                <input type="text" class="form-control" value="{{ $data->name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email</label>
                                <input type="email" class="form-control" value="{{ $data->user->email }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">No telp</label>
                                <input type="email" class="form-control" value="{{ $data->phone_number }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Alamat</label>
                                <input type="text" class="form-control" value="{{ $data->address }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">SIMPAN PROFILE</button>
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
                <form action="" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
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
                                <input type="text" class="form-control" value="{{ $data->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="bmd-label-floating">Email</label>
                                <input type="text" class="form-control" value="{{ $data->user->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="bmd-label-floating">Bisnis</label>
                                <input type="text" class="form-control" disabled value="{{ $data->business->business_name }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Update Profile</button>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        @endrole
    </div>
</div>
</div>

@endsection