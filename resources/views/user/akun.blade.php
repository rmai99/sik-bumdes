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
                    <thead>
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
                    </thead>
                    <tbody>
                        @foreach ($c->account as $account)
                            <tr>
                                <td class="text-center" style="width:5%" class="p-2">{{ $account->account_code }}</td>
                                <td style="width:60%" class="p-2">{{ $account->account_name }}</td>
                                <td>{{ $account->position }}</td>
                                <td style="width:10%" class="text-center">
                                    <form action="{{ route('akun.destroy', $account->id) }}" method="post">
                                        <button class="btnEditAccount btn-icon" type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editAkunModal" value="{{ $account->id }}" parent="{{ $p->id }}" classification="{{ $c->id }}">
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
                                <select class="form-control" name="parent">
                                    <option value="0" disabled="true" selected="true">Select Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option id="parentAkun" name="parentAkun" value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Kode Klasifikasi Akun</h6>
                                <input type="text" class="form-control" id="" aria-describedby="kodeKlasifikasiAkun"
                                    placeholder="ex. 11" name="code">
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Klasifikasi Akun</h6>
                                <input type="text" class="form-control" id="" aria-describedby="namaKlasifikasiAkun"
                                    placeholder="ex. aset lancar" name="name">
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

                            <div class="form-group parentAccount_">
                                <h6 class="text-dark font-weight-bold m-0">Parent Account</h6>
                                <select class="form-control" name="parent">
                                    <option value="0" disabled="true" selected="true">Select Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option id="parentAkun" name="parentAkun" value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group classificationCode">
                                <h6 class="text-dark font-weight-bold m-0">Kode Klasifikasi Akun</h6>
                                <input type="text" class="form-control" name="code" aria-describedby="kodeKlasifikasiAkun"
                                    value="11">
                            </div>

                            <div class="form-group classificationName">
                                <h6 class="text-dark font-weight-bold m-0">Nama Klasifikasi Akun</h6>
                                <input type="text" class="form-control" name="name" aria-describedby="namaKlasifikasiAkun"
                                    value="aset lancar">
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
                                <select class="form-control changeParent_" id="">
                                    <option disabled="true" selected="true">Parent Akun</option>
                                    @foreach ($account_parent as $a)
                                        <option value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Klasifikasi Akun</h6>
                                <select class="form-control classification_" name="classificationAkun">
                                    <option disabled="true" selected="true">Klasifikasi Akun</option>
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
                                    <input type="text" class="form-control" name="codeAkun" aria-describedby="kodeAkun"
                                        placeholder="ex. 1110">
                                </div>

                                <div class="form-group col-6">
                                    <h6 class="text-dark font-weight-bold m-0">Posisi Normal</h6>
                                    <select class="form-control" name="position">
                                        <option disabled="true" selected="true">Posisi</option>
                                        <option value="Debit">Debit</option>
                                        <option value="Kredit">Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="akun" aria-describedby="namaAkun"
                                    placeholder="ex. kas di bank">
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
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group parent">
                                <h6 class="text-dark font-weight-bold m-0">Parent Akun</h6>
                                <select class="form-control changeParent_" name="">
                                    <option value="0" disabled="true" selected="true">Select Parent</option>
                                    @foreach ($account_parent as $a)
                                        <option value="{{$a->id}}">
                                            {{$a->parent_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group classification">
                                <h6 class="text-dark font-weight-bold m-0">Klasifikasi Akun</h6>
                                <select class="form-control classification_" name="id_classification">
                                    <option value="0" disabled="true" selected="true">Select Parent</option>
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
                                    <input type="text" class="form-control" name="numberCode" aria-describedby="kodeAkun"
                                        value="1110">
                                </div>

                                <div class="form-group col-6 position">
                                    <h6 class="text-dark font-weight-bold m-0">Posisi Normal</h6>
                                    <select class="form-control" name="position">
                                        <option disabled="true" selected="true">Posisi</option>
                                        <option value="Debit">Debit</option>
                                        <option value="Kredit">Kredit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group accountName">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" name="name" aria-describedby="namaAkun"
                                    value="kas di bank">
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
        $(document).on('click', '.btnEditClassification', function () {
            var id = $(this).attr('value');
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
            var id = $(this).attr('value');
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
                    op+='<option value="0" disabled="true" selected="true">Select Classification</option>';
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
            
    });
</script>
@include('sweetalert::alert')
@endpush