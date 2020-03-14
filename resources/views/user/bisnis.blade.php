@extends('user/layout/template')

@section('title', 'Profile')

@section('title-page', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">Bisnis</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="" class="btn btn-sm btn-primary addBusiness">Tambah Bisnis</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Tanggal Dibentuk
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($business as $item)
                                    <tr>
                                        <td>
                                            {{ $item->business_name}}
                                        </td>
                                        <td>
                                            {{ $item->created_at}}
                                        </td>
                                        <td class="td-actions text-right">
                                            <form action="{{ route('bisnis.destroy', $item->id) }}" method="post">
                                                <button type="button" class="edit" value="{{$item->id}}" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editBusiness">
                                                    <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                                </button>
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                                        <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambah Bisnis --}}
<div class="modal fade" id="tambahBisnis" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <form class="form" method="POST" action="{{ route('bisnis.store') }}">
                @csrf
                <div class="card card-signup card-plain">
                    <div class="modal-header">
                        <div class="card-header card-header-primary text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i></button>
                            <h4 class="card-title">Tambah Bisnis</h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama</h6>
                                <input type="text" class="form-control" name="business_name" aria-describedby="date" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-round text-center">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Bisnis --}}
<div class="modal fade" id="editBusiness" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <form class="form" method="POST" action="" id="formEdit">
                {{ method_field('PUT') }}
                @csrf
                <div class="card card-signup card-plain">
                    <div class="modal-header">
                        <div class="card-header card-header-primary text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i></button>
                            <h4 class="card-title">Edit Bisnis</h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group name">
                                <h6 class="text-dark font-weight-bold m-0">Nama</h6>
                                <input type="text" class="form-control" aria-describedby="name" name="name">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-round text-center">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $(document).on('click', '.edit', function() {
            var id = $(this).attr('value');
            $.ajax({
                type        : 'GET',
                url         : '{!!URL::to('detail_bisnis')!!}',
                data        : {'id' : id},
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);
                    $.each(servers, function(index, value){
                        var name = value.business_name;
                        
                        $('div.name input').val(name);
                    });
                }, error    : function(){

                },
            })
            var action = "{{route('bisnis.index')}}/"+id;
            $('#formEdit').attr('action', action);

        });

        $(document).on('click', '.addBusiness', function(e) {
            e.preventDefault();
            $.ajax({
                type        : 'GET',
                url         : '{!!URL::to('cekpro')!!}',
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);
                    // console.log(servers);
                    // console.log(servers.result);
                    if (servers.result == "REGULER") {
                        swal.fire("PRO AKUN")
                    }else {
                        $('#tambahBisnis').modal('show')
                    }
                }, error    : function(){

                },
            })
        });
    });
</script>
@include('sweetalert::alert')
@endpush