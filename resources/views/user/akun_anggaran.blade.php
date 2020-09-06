@extends('user/layout/template')

@section('title', 'Rencana Anggaran Bisnis')

@section('title-page', 'Rencana Anggaran Bisnis')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pt-3 pb-3 mr-1 d-flex justify-content-end">
                            <div class="col-2 p-0">
                                <button type="button" class="btn btn-primary m-1 pl-2 pr-2" data-toggle="modal"
                                        data-target="#akunAnggaran" style="float:right;">Tambah Akun</button>
                            </div>
                        </div>
                        <div class="card-header card-header-warning m-1 p-2 d-flex justify-content-between"
                            data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false"
                            aria-controls="collapse1">
                            <h4 class="card-title mb-0">Penerimaan</h4>
                            <i class="material-icons">keyboard_arrow_down</i>
                        </div>

                        <div class="card-body collapse pt-0 pb-0 mb-0" id="collapse1">
                            <table class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0"
                                width="100%">
                                @foreach ($account as $item)
                                <tr>
                                    <th class="p-2">
                                        <strong>{{$item->name}}</strong>
                                    </th>
                                    <th></th>
                                </tr>
                                @foreach ($item->budget_account as $b)
                                <tr>
                                    <td style="width:10%" class="p-2">
                                        {{$b->name}}
                                    </td>
                                    <td style="width:15%" class="text-right">
                                        <button class="btnEditAccount btn-icon" type="button" rel="tooltip"
                                            title="Edit" data-toggle="modal" data-target="#editAkunModal"
                                            value="{{$b->id}}">
                                            <i class="material-icons"
                                                style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                        </button>
                                        <button type="button" class="btn-icon remove" value="{{$b->id}}">
                                            <i class="material-icons"
                                                style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </table>
                        </div>
                        <div class="card-header card-header-warning m-1 p-2 d-flex justify-content-between"
                            data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false"
                            aria-controls="collapse2">
                            <h4 class="card-title mb-0">Belanja</h4>
                            <i class="material-icons">keyboard_arrow_down</i>
                        </div>

                        <div class="card-body collapse pt-0 pb-0 mb-0" id="collapse2">
                            <table class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0"
                                width="100%">
                                @foreach ($type as $item)
                                    <tr>
                                        <td style="width:10%" class="p-2">
                                            {{$item->name}}
                                        </td>
                                        <td style="width:15%" class="text-right">
                                            <button class="btnEditAccount btn-icon" type="button" rel="tooltip"
                                                title="Edit Akun" data-toggle="modal" data-target="#editAkunModal"
                                                value="{{$item->id}}">
                                                <i class="material-icons"
                                                    style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                            <button type="button" class="btn-icon remove" value="{{$item->id}}">
                                                <i class="material-icons"
                                                    style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <!-- end content-->
                </div>
                <!--  end card  -->
            </div>
            <!-- end col-md-12 -->
        </div>
        <!-- end row -->
    </div>
</div>
{{-- Modal Add Akun --}}
<div class="modal fade" id="akunAnggaran" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Akun Anggaran</h4>
                    </div>
                </div>
                <form class="form" action="{{ route('akun_anggaran.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Belanja/Penerimaan</h6>
                                <select class="form-control type" name="type" required>
                                    <option value="" selected="true" required>Pilih Type</option>
                                    <option value="Penerimaan">Penerimaan</option>
                                    <option value="Belanja">Belanja</option>
                                    
                                </select>
                            </div>

                            <div class="form-group kategori">
                                <h6 class="text-dark font-weight-bold m-0">Kategori</h6>
                                <select class="form-control" name="kategori">
                                    <option></option>
                                </select>
                                @error('input_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="namaAkunAnggaran" value="{{ old('namaAkunAnggaran') }}" required>
                            </div>
                            @error('namaAkunAnggaran')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Modal Edit Akun --}}
<div class="modal fade" id="editAkunModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Akun Anggaran</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="formAccount">
                    {{method_field('PUT')}}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group type_edit">
                                <h6 class="text-dark font-weight-bold m-0">Belanja/Penerimaan</h6>
                                <select class="form-control type" name="type" id="select" required>
                                    <option value="" selected="true" required>Pilih Type</option>
                                    <option value="Penerimaan">Penerimaan</option>
                                    <option value="Belanja">Belanja</option>
                                    
                                </select>
                            </div>

                            <div class="form-group kategori">
                                <h6 class="text-dark font-weight-bold m-0">Kategori</h6>
                                <select class="form-control" name="kategori">
                                    <option></option>
                                </select>
                                @error('input_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group name">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="namaAkunAnggaran" value="{{ old('namaAkunAnggaran') }}" required>
                            </div>
                            @error('namaAkunAnggaran')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('change', '.type', function(e){
        e.preventDefault();
        var type = $(this).val();
        var op=`<option value="1">Penerimaan Pendapatan BUMDes</option>
        <option value="2">Penerimaan Penyertaan Modal</option>
        <option value="3">Penerimaan dari Pembiayaan</option>`
        if(type == "Belanja"){
            $('div.kategori select').append('<option value="" selected="true" >Pilih Kategori</option>');
            $('div.kategori select').prop('disabled', true);
        } else {
            $('div.kategori select').html(" ");
            $('div.kategori select').append(op)
            $('div.kategori select').prop('disabled', false);
        }
    });
    $(document).on('click', '.btnEditAccount', function () {
        var id = $(this).attr('value');
        console.log(id);
        $.ajax({
            type        : 'GET',
            url         : '{!!url('detail_akun_anggaran')!!}',
            data        : {'id':id},
            dataType    : 'html',
            success     : function(data){
                var servers = $.parseJSON(data);
                console.log(servers);
                $.each(servers, function(index, value){
                    var op=`<option value="1">Penerimaan Pendapatan BUMDes</option>
                            <option value="2">Penerimaan Penyertaan Modal</option>
                            <option value="3">Penerimaan dari Pembiayaan</option>`
                    if(value.type == "Belanja"){
                        $('div.kategori select').append('<option value="" selected="true" >Pilih Kategori</option>');
                        $('div.kategori select').prop('disabled', true);
                    } else {
                        $('div.kategori select').html(" ");
                        $('div.kategori select').append(op);
                        $('div.kategori select').prop('disabled', false);
                    }
                    var type = value.type;
                    var name = value.name;
                    var kategori = value.id_category;
                    $('div.type_edit select').val(type);
                    
                    // $('div.id_account select').val(id_account);
                    $("div.name input").val(name);
                    $("div.kategori select").val(kategori);

                });
            }, error : function(){

            },
        });
        
        var action = "{{route('akun_anggaran.index')}}/"+id;
        $('#formAccount').attr('action', action);

    });
    $(document).on('click', '.remove', function(e) {   
        e.preventDefault();
        var id = $(this).attr('value');
        
        // console.log(sid);
        var url = "{{ route('akun_anggaran.index') }}/"+id;
        Swal.fire({
            title : 'Anda yakin menghapus akun untuk anggaran?',
            text : 'Anda tidak dapat mengembalikan data yang telah dihapus!',
            icon : 'warning',
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
                            'Hapus!',
                            'Akun untuk anggaran telah dihapus.',
                            'success'
                        )
                        $(this).closest('tr').remove();
                    }
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
</script>
@include('sweetalert::alert')
@endpush