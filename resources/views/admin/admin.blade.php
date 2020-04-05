@extends('admin/layout/template')

@section('title', 'Admin')

@section('title-page', 'Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">Admin</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Email
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Maida
                                    </td>
                                    <td>
                                        maida@maida.com
                                    </td>
                                    <td class="text-right">
                                        <form method="post">
                                            <button type="button" class="edit btn-icon" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editBusiness">
                                                <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" onclick="return confirm('Anda yakin mau menghapus item ini ?')" class="btn-icon">
                                                    <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('jss')
@include('sweetalert::alert')
@endpush