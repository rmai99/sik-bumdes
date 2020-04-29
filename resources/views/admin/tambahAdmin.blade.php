@extends('admin/layout/template')

@section('title', 'Admin')

@section('title-page', 'Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" class="form-horizontal" action="{{route('admin.manajemen_admin.store')}}">
                @csrf
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Tambah Admin</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" name="email" id="input-email" type="email"
                                        placeholder="Email" value="" required />
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif 
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-2 col-form-label" for="input-password">Password</label>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <input class="form-control" input type="password" name="password"
                                        id="input-password" placeholder="Password" value="" required />
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif 
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ml-auto mr-auto">
                        <button type="submit" class="btn btn-primary">Tambah Admin</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection