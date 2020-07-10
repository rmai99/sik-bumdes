@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="text-center mt-3 mb-1">
                    <h3> SIK <strong> BUMDes </strong> </h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('bisnis.store')}}">
                        <h5 class="font-weight-bold">Informasi Bisnis</h5>
                        @csrf
                        <p class="mb-0">Nama Bisnis</p>
                        <div class="input-group">
                            <input class="form-control" name="business_name" type="text" 
                                required="true" aria-required="true" />
                        </div>
                        @if ($errors->has('business_name'))
                            <span class="invalid">
                                <strong>{{ $errors->first('business_name') }}</strong>
                            </span>
                        @endif
                        <div class="row  mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-register mb-3">Tambah Bisnis</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
