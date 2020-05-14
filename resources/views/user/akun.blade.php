@extends('user/layout/template')

@section('title', 'Akun')

@section('title-page', 'Akun')

@section('content')
<div class="card p-4">
    <div class="row pt-3 pb-3 mr-1 d-flex justify-content-end">
        <div class="col-2 p-0">
            <a class="btn btn-primary dropdown-toggle float-right mb-2" href="#" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Tambah
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" data-toggle="modal" data-target="#klasifikasiModal">Tambah Klasifikasi</a>
                <a class="dropdown-item" data-toggle="modal" data-target="#akunModal">Tambah Akun</a>
            </div>
        </div>
    </div>
    @foreach ($account_parent as $p)
        <div class="card-header card-header-warning m-1 p-2 d-flex justify-content-between" data-toggle="collapse" href="#collapse{{ $p->id }}" role="button"
            aria-expanded="false" aria-controls="collapse{{ $p->id }}">
            <h4 class="card-title mb-0">{{ $p->parent_name}}</h4>
            <i class="material-icons">keyboard_arrow_down</i>
            {{--  <p class="card-category">New employees on 15th September, 2016</p>  --}}
        </div>
    
        <div class="card-body collapse pt-0 pb-0 mb-0" id="collapse{{ $p->id }}">
            @foreach ($p->classification as $c)
                <table class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0" width="100%">
                    <tr>
                        <th class="p-2 text-center" style="width:8%"><strong>{{ $c->classification_code }}</strong></th>
                        <th class="p-2"><strong>{{ $c->classification_name }}</strong></th>
                        <th></th>
                        <th style="width:10%" class="text-center">
                            <form action="{{ route('classification.destroy', $c->id) }}" method="post">
                                <button class="btnEditClassification btn-icon" type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editKlasifikasiModal"
                                    value="{{ $c->id }}" data-parent= "{{ $c->id_parent }}">
                                    <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                </button>
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                <button type="submit" rel="tooltip" title="Remove" onclick="return confirm('Anda yakin mau menghapus item ini ?')" class="btn-icon">
                                    <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                </button>
                            </form>
                        </th>
                    </tr>    
                    <tbody>
                        @foreach ($c->account as $account)
                            <tr>
                                <td class="text-center" style="width:5%" class="p-2">{{ $account->account_code }}</td>
                                <td style="width:60%" class="p-2">{{ $account->account_name }}</td>
                                <td>{{ $account->position }}</td>
                                <td style="width:10%" class="text-center">
                                    <button class="btnEditAccount btn-icon" type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editAkunModal" value="{{ $account->id }}" parent="{{ $p->id }}" classification="{{ $c->id }}">
                                        <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                    </button>
                                    <button type="button" class="btn-icon remove" id="{{ $account->id }}">
                                            <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @endforeach
</div>

{{-- Modal Add Klasifikasi --}}
<div class="modal fade" id="klasifikasiModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Klasifikasi</h4>
                    </div>
                </div>
                <form class="form" action="{{ route('classification.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Parent Account</h6>
                                <select class="form-control parent" name="input_parent" required>
                                    <option value="" selected="true">Pilih Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option id="parentAkun" value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Kode Klasifikasi Akun</h6>
                                <input type="text" class="form-control" aria-describedby="kodeKlasifikasiAkun"
                                    placeholder="ex. 11" name="input_code" value="{{ old('input_code') }}" required>
                            </div>
                            @error('input_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Klasifikasi Akun</h6>
                                <input type="text" class="form-control" aria-describedby="namaKlasifikasiAkun"
                                    placeholder="ex. aset lancar" name="input_name" value="{{ old('input_name') }}" required>
                            </div>
                            @error('input_name')
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

{{-- Edit Klasifikasi Akun --}}
<div class="modal fade" id="editKlasifikasiModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Edit Klasifikasi</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="formClassification" enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="classification" id="id_classification">
                            <div class="form-group parentAccount_">
                                <h6 class="text-dark font-weight-bold m-0">Parent Account</h6>
                                <select class="form-control" name="edit_parent" id="parent" required>
                                    <option value="" selected="true" required>Pilih Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option id="parentAkun" name="parentAkun" value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group classificationCode">
                                <h6 class="text-dark font-weight-bold m-0">Kode Klasifikasi Akun</h6>
                                <input type="text" class="form-control" name="edit_code" aria-describedby="kodeKlasifikasiAkun"
                                    value="11" required id="code">        
                                @error('edit_code')
                                    <span class="invalid-feedback" role="alert" id="hapus">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group classificationName">
                                <h6 class="text-dark font-weight-bold m-0">Nama Klasifikasi Akun</h6>
                                <input type="text" class="form-control" name="edit_name" aria-describedby="namaKlasifikasiAkun"
                                    value="aset lancar"id="name" required>
                            </div>
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

{{-- Modal Tambah Akun --}}
<div class="modal fade" id="akunModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Akun</h4>
                    </div>
                </div>
                <form class="form" action="{{ route('akun.store') }}" method="POST">
                    <div class="modal-body">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Parent Akun</h6>
                                <select class="form-control changeParent_" name="input_parentAccount" required>
                                    <option selected="true" value="">Parent Akun</option>
                                    @foreach ($account_parent as $a)
                                        <option value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Klasifikasi Akun</h6>
                                <select class="form-control classification_" name="input_classificationAccount" required>
                                    <option selected="true" value="">Klasifikasi Akun</option>
                                    @foreach ($account_parent as $a)
                                        @foreach ($a->classification as $classification)
                                            <option value="{{$classification->id}}">
                                                {{$classification->classification_name}}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <h6 class="text-dark font-weight-bold m-0">Kode Akun</h6>
                                    <input type="text" class="form-control" name="input_codeAccount" aria-describedby="kodeAkun"
                                        placeholder="ex. 1110" value="{{ old('input_codeAccount') }}" required>
                                        @error('input_codeAccount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>

                                <div class="form-group col-6">
                                    <h6 class="text-dark font-weight-bold m-0">Posisi Normal</h6>
                                    <select class="form-control positionAccount" name="input_positionAccount" required>
                                        <option selected="true" value="">Posisi</option>
                                        <option value="Debit" {{ old('input_positionAccount') }} == Debit ? 'selected' : ''>Debit</option>
                                        <option value="Kredit" {{ old('input_positionAccount') }} == Kredit ? 'selected' : ''>Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="input_nameAccount" aria-describedby="input_nameAccount"
                                    placeholder="ex. kas di bank" required value="{{ old('input_nameAccount') }}">
                            </div>

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

{{-- Edit Akun Modal --}}
<div class="modal fade" id="editAkunModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Edit Akun</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="formAccount">
                    {{method_field('PUT')}}
                    @csrf
                    <input type="hidden" class="form-control" name="account" id="id_account">
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group parent">
                                <h6 class="text-dark font-weight-bold m-0">Parent Akun</h6>
                                <select class="form-control changeParent_" name="edit_parentAccount" required>
                                    <option value="" selected="true">Select Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group classification">
                                <h6 class="text-dark font-weight-bold m-0">Klasifikasi Akun</h6>
                                <select class="form-control classification_" name="edit_classificationAccount" required>
                                    <option value="" selected="true">Select Parent</option>
                                    @foreach ($account_parent as $a)
                                        @foreach ($a->classification as $classification)
                                            <option value="{{$classification->id}}">
                                                {{$classification->classification_name}}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-6 acountCode">
                                    <h6 class="text-dark font-weight-bold m-0">Kode Akun</h6>
                                    <input type="text" class="form-control" name="edit_codeAccount" aria-describedby="kodeAkun"
                                        value="{{ old('edit_codeAccount') }}" required>
                                    @error('edit_codeAccount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-6 position">
                                    <h6 class="text-dark font-weight-bold m-0">Posisi Normal</h6>
                                    <select class="form-control positionAccount" name="edit_positionAccount" required>
                                        <option disabled="true" selected="true">Posisi</option>
                                        <option value="Debit" {{ old('edit_positionAccount') }} == Debit ? 'selected' : ''>Debit</option>
                                        <option value="Kredit" {{ old('edit_positionAccount') }} == Kredit ? 'selected' : ''>Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group accountName">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="edit_nameAccount" aria-describedby="namaAkun"
                                    value="{{ old('edit_nameAccount') }}" required>
                            </div>
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
<script type="text/javascript">
    $(document).ready(function () {
        @if ($errors->has('input_code'))
            $('#klasifikasiModal').modal('show');
        @endif
        @if ($errors->has('edit_code'))
            $('#editKlasifikasiModal').modal('show');
            var parent = "{{old('edit_parent')}}";
            var code = "{{old('edit_code')}}";
            var name = "{{old('edit_name')}}";
            $('#parent').val(parent);
            $('#code').val(code);
            $('#name').val(name);
            
            var id = "{{old('classification')}}";
            var action = "{{route('classification.index')}}/"+id;
            $('#formClassification').attr('action', action);
        @endif
        @if ($errors->has('input_codeAccount'))
            var id_parent = {{old('input_parentAccount')}};
            var id_classification = {{old('input_classificationAccount')}};
            var position = "{{old('input_positionAccount')}}";
            $('select.changeParent_').val(id_parent);
            $('select.classification_').val(id_classification);
            $('select.positionAccount').val(position);
            $('#akunModal').modal('show');
        @endif
        @if ($errors->has('edit_codeAccount'))
            $('#editAkunModal').modal('show');
            var id_parent = {{old('edit_parentAccount')}};
            var id_classification = {{old('edit_classificationAccount')}};
            var position = "{{old('edit_positionAccount')}}";
            var name = "{{old('edit_nameAccount')}}";
            console.log(name);
            $('select.changeParent_').val(id_parent);
            $('select.classification_').val(id_classification);
            $('select.positionAccount').val(position);
            var id = "{{old('account')}}";
            var action = "{{route('akun.index')}}/"+id;
            $('#formAccount').attr('action', action);
        @endif

        $(document).on('click', '.btnEditClassification', function () {
            @if ($errors->has('edit_code'))            
                $('span.invalid-feedback').remove();
            @endif
            var id = $(this).attr('value');
            $('#id_classification').val(id);
            $.ajax({
                type        : 'get',
                url         : '{!!URL::to('detailClassification')!!}',
                data        : {'id':id},
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);

                    $.each(servers, function(index, value){
                        var numberCode = value.classification_code;
                        var nameCode = value.classification_name;
                        var id_parent = value.id_parent ;

                        $('div.classificationCode input').val(numberCode);
                        $('div.classificationName input').val(nameCode);
                        $("div.parentAccount_ select").val(id_parent);

                    });
                }, error : function(){

                },
            });
            
            var action = "{{route('classification.index')}}/"+id;
            $('#formClassification').attr('action',action);
        });

        $(document).on('click', '.btnEditAccount', function () {
            @if ($errors->has('edit_codeAccount'))            
                $('span.invalid-feedback').remove();
            @endif
            var id = $(this).attr('value');
            $('#id_account').val(id);
            var parent = $(this).attr('parent');
            var test = $(this).attr('classification');

            var div= $(".classification");
            var op=" ";

            $("div.parent select").val(parent);
            $.ajax({
                type        : 'GET',
                url         : '{!!URL::to('detailAccount')!!}',
                data        : {'id':id},
                dataType    : 'html',
                success     : function(data){
                    var servers = $.parseJSON(data);
                    $.each(servers, function(index, value){
                        var classification = value.id_classification;
                        var account_code = value.account_code;
                        var account_name = value.account_name;
                        var position = value.position;

                        $('div.classification select').val(classification);
                        $('div.acountCode input').val(account_code);
                        $("div.accountName input").val(account_name);
                        $("div.position select").val(position);

                    });
                }, error : function(){

                },
            });

            $.ajax({
                type        : 'GET',
                url         : '{!!URL::to('findClassification')!!}',
                data        : {'id':parent},
                success:function(data){
                    op+='<option value="0" disabled="true"="true">Select Classification</option>';
                    for(var i=0;i<data.length;i++){
                    if (data[i].id == test) {
                        var x = "selected";
                    } else {
                        var x = "";
                    }
                    op+='<option '+x+' value="'+data[i].id+'">'+data[i].classification_name+'</option>'
                    }

                    $('div.classification select').html(" ");
                    $('div.classification select').append(op);
                },
                error:function(){

                }
            });

            var action = "{{route('akun.index')}}/"+id;
            $('#formAccount').attr('action',action);
        });

        $(document).on('change', '.changeParent_', function(){
            var parent = $(this).val();
            console.log(parent);
            
            var div= $(this).parent().parent();
            var op=" ";
            
            $.ajax({
                type        : 'GET',
                url         : '{!!URL::to('findClassification')!!}',
                data        : {'id':parent},
                success:function(data){
                    op+='<option value="" selected="true">Select Classification</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].classification_name+'</option>'
                    }
                    console.log(op);

                    div.find('.classification_').html(" ");
                    div.find('.classification_').append(op);
                },
                error:function(){
    
                }
            });

        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Delete a record
        $(document).on('click', '.remove', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            
            // console.log(sid);
            var url = "{{ route('akun.index') }}/"+id;
            Swal.fire({
                title: 'Anda yakin ingin menghapus bisnis?',
                text: "Data akan dihapus secara permanen",
                icon: 'warning',
                showCancelButton: true,
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
                            console.log(url);
                            $(this).closest('tr').remove();
                        }
                    });
                }
            })
        });
    });
</script>

@include('sweetalert::alert')

@endpush