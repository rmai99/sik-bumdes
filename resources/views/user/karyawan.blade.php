@extends('user/layout/template')

@section('title', 'Karyawan')

@section('title-page', 'Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">Karyawan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href=""
                                class="btn btn-sm btn-primary addEmployee" id="hmm">Tambah Karyawan</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>
                                    Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Tanggal Terdaftar
                                </th>
                                <th>
                                    Bisnis
                                </th>
                                <th class="text-right">
                                    Actions
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($employee as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->user->email }}
                                        </td>
                                        <td>
                                            2019-04-08
                                        </td>
                                        <td>
                                            {{ $item->business->business_name }}
                                        </td>
                                        <td class="td-actions text-right">
                                            <form action="{{ route('karyawan.destroy', $item->id) }}" method="post">
                                                <button type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editEmployee" value="{{ $item->id }}" class="btnEditEmployee btn-icon">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editEmployee" tabindex="-1" role="">
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
                            <h4 class="card-title">Edit Karyawan</h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group name">
                                <h6 class="text-dark font-weight-bold m-0">Nama</h6>
                                <input type="text" class="form-control" aria-describedby="name" name="name">
                            </div>
                            <div class="form-group email">
                                <h6 class="text-dark font-weight-bold m-0">Email</h6>
                                <input type="text" class="form-control" aria-describedby="email" name="email" disabled>
                            </div>
                            <div class="form-group business">
                                <h6 class="text-dark font-weight-bold m-0">Bisnis</h6>
                                <select class="form-control" name="id_business">
                                    <option value="0" disabled="true" selected="true">Pilih Bisnis</option>
                                    @foreach ($business as $item)
                                        <option id="business" value="{{$item->id}}">
                                            {{$item->business_name}}
                                        </option>
                                    @endforeach
                                </select>
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
        $(document).ready(function(){
            $(document).on('click', '.addEmployee', function(e){
                e.preventDefault();
                $.ajax({
                    type        :'GET',
                    url         : '{!!URL::to('cekpro')!!}',
                    dataType    : 'html',
                    success     : function(data){
                        var servers = $.parseJSON(data);
                        if(servers.result == "REGULER"){
                            swal.fire({
                                title: "Ubah Akun Menjadi PRO",
                                icon: "warning",
                                closeOnClickOutside: false,
                                showConfirmButton: false,
                                // timer       :2000,
                                footer: '<a href="/home">Upgrade Account?</a>'
                            })
                        } else {
                            var href="{{ route('karyawan.create') }}"
                            window.location.href = href;
                        }
                    }, error    : function(){
                        
                    },
                })
            })
            $(document).on('click', '.btnEditEmployee', function(){
                var id = $(this).attr('value');
                console.log(id);
                $.ajax({
                    type        : 'GET',
                    url         : '{!!URL::to('detailEmployee')!!}',
                    data        : {'id' : id},
                    dataType    : 'html',
                    success     : function(data){
                        var servers = $.parseJSON(data);

                        $.each(servers, function(index, value){
                            var name = value.name;
                            var email = value.user.email;
                            var business = value.business.id;

                            $('div.name input').val(name);
                            $('div.email input').val(email);
                            $('div.business select').val(business);
                        });
                    }, error : function(){

                    },
                });
                var action = "{{route('karyawan.index')}}/"+id;
                $('#formEdit').attr('action',action);
            })
        })
    </script>
    @include('sweetalert::alert')
@endpush