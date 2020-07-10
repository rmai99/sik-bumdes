@extends('user/layout/template')

@section('title', 'Bisnis')

@section('title-page', 'Bisnis')

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
                        <table class="table table-striped table-no-bordered table-hover" id="datatables">
                            <thead class=" text-primary">
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Tanggal Dibentuk
                                </th>
                                <th class="disabled-sorting text-right">
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
                                        <td class="text-right">
                                            <button type="button" class="edit btn-icon" id="{{$item->id}}" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editBusiness">
                                                <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                            <button type="button" class="btn-icon remove" id="{{$item->id}}" >
                                                    <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </button>
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
                                <input type="text" class="form-control" name="business_name" aria-describedby="date" placeholder="" value="{{old('business_name')}}" required>
                                @error('business_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                                <input type="text" class="form-control" aria-describedby="name" name="name" id="name">
                                <input type="hidden" class="form-control" name="id" id="formEditId">
                            </div>
                            @if ($errors->has('name'))
                                <span class="invalid">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
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
@endsection`

@push('js')
<script>
    $(document).ready(function () {
        @if ($errors->has('disable'))
            swal.fire({
                title: "Ubah Akun Menjadi PRO",
                icon: "warning",
                closeOnClickOutside: false,
                showConfirmButton: false,
                // timer       :2000,
                footer: '<a href="{{route('upgrade')}}">Upgrade Account?</a>'
            })
        @endif
        @if ($errors->has('business_name'))
            $('#tambahBisnis').modal('show');
        @endif
        @if ($errors->has('name'))
            $('#editBusiness').modal('show');
            var name = "{{old('name')}}";
            $('#name').val(name);
            
            var id = "{{old('id')}}";
            var action = "{{route('bisnis.index')}}/"+id;
            $('#formEdit').attr('action', action);
        @endif
        
        $('#datatables').DataTable({
            "pagingType"        : "full_numbers",
            "lengthMenu"        : [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                                ],
            responsive          : true, 
            language            : {
            search              : "_INPUT_",
            searchPlaceholder   : "Cari",
            }
        });

        var table = $('#datatables').DataTable();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Delete a record
        table.on('click', '.remove', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            
            // console.log(sid);
            var url = "{{ route('bisnis.index') }}/"+id;
            Swal.fire({
                title: 'Anda yakin ingin menghapus bisnis?',
                text: "Data akan dihapus secara permanen",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: (response) => {
                            Swal.fire(
                            'Dihapus!',
                            'Bisnis telah dihapus.',
                            'success'
                            )
                            $(this).closest('tr').remove();
                            var url = "{{route('bisnis.create')}}";
                            window.location.href = url;
                        }, error    : function(){
                            swal.fire({
                                title: "Tidak dapat menghapus bisnis",
                                icon: "warning",
                                closeOnClickOutside: false,
                                showConfirmButton: false,
                                timer       :2000,
                            })
                        },
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Batal',
                    'Data batal dihapus :)',
                    'error'
                    )
                }
            })
        });
    });
    
    $(document).ready(function () {
        $(document).on('click', '.edit', function() {
            var id = $(this).attr('id');
            $('#formEditId').val(id);
            $.ajax({
                type        : 'GET',
                url         : '{!!url('detail_bisnis')!!}',
                data        : {'id' : id},
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);
                    $.each(servers, function(index, value){
                        var name = value.business_name;
                        
                        $('#name').val(name);
                    });
                }
            })
            var action = "{{route('bisnis.index')}}/"+id;
            $('#formEdit').attr('action', action);
        });

        $(document).on('click', '.addBusiness', function(e) {
            e.preventDefault();
            $.ajax({
                type        : 'GET',
                url         : '{!!url('isPro')!!}',
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);
                    // console.log(servers);
                    // console.log(servers.result);
                    if (servers.result == "REGULER") {
                        swal.fire({
                            title: "Ubah Akun Perusahaan Menjadi PRO",
                            icon: "warning",
                            closeOnClickOutside: false,
                            showConfirmButton: false,
                            // timer       :2000,
                            footer: '<a href="{{route('upgrade')}}">Upgrade Akun Perusahaan?</a>'
                        });
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