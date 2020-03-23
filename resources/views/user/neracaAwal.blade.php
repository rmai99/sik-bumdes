@extends('user/layout/template')

@section('title', 'Neraca Awal')

@section('title-page', 'Neraca Awal')

@section('content')
@php
    if (isset($_GET['year'])) {
        $dt = $_GET['year'];
    } else {
        $dt = date('Y');
    }
    $jumlah_debit = 0;
    $jumlah_kredit = 0;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Neraca Awal</h3>
                        <p class=""><strong>Periode</strong> {{$year}} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="pl-1 padding-select groupbyYear" id="test" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Year</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 mt-3 pr-0">
                                    <button type="button" class="btn btn-primary m-1 pl-2 pr-2" data-toggle="modal"
                                        data-target="#neracaAwalModal" style="float:right;">Tambah Neraca Awal</button>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-group">
                                        @foreach ($errors->all() as $error)
                                            <li style="list-style-type: none;">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Akun</th>
                                        <th>Debit</th>
                                        <th class="text-center">Kredit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Akun</th>
                                        <th>Debit</th>
                                        <th class="text-center">Kredit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                @foreach ($account_parent as $p)
                                    <tr data-toggle="collapse" data-target=".data{{ $p->id }}" class="accordion-toggle">
                                        <td style="width:60%">
                                            <strong> {{ $p->parent_code }} - {{ $p->parent_name }} </strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"><i class="material-icons float-right" style="opacity: 30%;">keyboard_arrow_down</i></td>
                                    </tr>
                                    @foreach ($p->classification as $c)
                                    <tr class="accordian-body collapse accordion-toggle data{{ $p->id }}">
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                            {{ $c->classification_code }} - {{ $c->classification_name }}
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @foreach ($initial_balance as $item)
                                    @if ($item->account->id_classification == $c->id)
                                    <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun{{ $c->id }}">
                                        <td style="width:60%;padding-left: 3rem!important;">
                                            {{ $item->account->account_code }} - {{ $item->account->account_name }}
                                        </td>
                                        <td style="width:15%">
                                            @if ($item->account->position=="Debit")
                                                Rp{{strrev(implode('.',str_split(strrev(strval($item->amount)),3)))}}
                                            @php
                                            $jumlah_debit +=$item->amount;
                                            @endphp
                                            @endif
                                        </td>
                                        <td style="width:15%">
                                            @if ($item->account->position=="Kredit")
                                                Rp{{strrev(implode('.',str_split(strrev(strval($item->amount)),3)))}}
                                            @php
                                            $jumlah_kredit +=$item->amount;
                                            @endphp
                                            @endif
                                        </td>
                                        <td style="width:10%">
                                            <form action="{{ route('neraca_awal.destroy', $item->id) }}" method="post">
                                                <button type="button" rel="tooltip" title="Edit Akun" data-toggle="modal"
                                                    data-target="#editNeracaAwalModal" class="editInitialBalance btn-icon"
                                                    value="{{ $item->id }}">
                                                    <i class="material-icons"
                                                        style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                                </button>
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                <button type="submit" rel="tooltip" title="Remove" onclick="return confirm('Yakin ingin menghapus data?')" class="btn-icon">
                                                    <i class="material-icons"style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                                </button>
                                            </form>
                                            
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </tbody>
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

{{-- Tambah Neraca Awal Modal--}}
<div class="modal fade" id="neracaAwalModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Neraca Awal</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="{{route('neraca_awal.store')}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                <input type="date" class="form-control" aria-describedby="date" placeholder="" name="date">
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <select class="w-100 pl-1 padding-select" name="id_account" style="border-radius: 3px;">
                                    <option value="0" selected disabled>Akun</option>
                                    @foreach ($account_parent as $data)
                                        @foreach ($data->classification as $item)
                                            @foreach ($item->account as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->account_code }} - {{ $akun->account_name }}</option>        
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                <input name="amount" type="number" class="form-control" id="harga" onkeyup="copytextbox();" value="{{session()->get('properti.harga') }}">
                                <input type="text" class="form-control" id="hasil" disabled>
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

{{-- Modal Edit Neraca Awal --}}
<div class="modal fade" id="editNeracaAwalModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Neraca Awal</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="editInitial">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group date">
                                <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                <input type="date" class="form-control" name="date" aria-describedby="date" placeholder="">
                            </div>

                            <div class="form-group id_account">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <select class="w-100 pl-1 padding-select" name="id_account" style="border-radius: 3px;">
                                    <option value="0" selected disabled>Akun</option>
                                    @foreach ($account_parent as $data)
                                        @foreach ($data->classification as $item)
                                            @foreach ($item->account as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->account_code }} - {{ $akun->account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group amount">
                                <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                <input type="number" class="form-control" aria-describedby="amountAccount" name="amount">
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
<script>
    $(document).ready(function () {
        $('#datatables').DataTable({
            "info":     false,
            "ordering": false,
            "bPaginate": false,
            responsive          : true,
            language            : {
            search              : "_INPUT_",
            searchPlaceholder   : "Cari",
            }
        });
        
        $("div.dataTables_length").html('<div class="col-md-4 pl-0"><div class="form-group"><strong class="mr-3">Tahun : </strong><select class="pl-1 padding-select groupbyYear" id="test" style="border-radius: 3px;"><option value="0" disabled="true" selected="true">Year</option>@foreach ($years as $y)<option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>@endforeach</select></div>');
    });

    $(document).on('change', '#test', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();

        var url = "{{route('neraca_awal.index')}}?year=" + year;
        window.location.href = url;

    });

    $(document).on('click', '.editInitialBalance', function () {
        var id = $(this).attr('value');
        $.ajax({
            type        : 'GET',
            url         : '{!!URL::to('detail_balance')!!}',
            data        : {'id':id},
            dataType    : 'html',
            success     : function(data){
                var servers = $.parseJSON(data);

                $.each(servers, function(index, value){
                    var date = value.date;
                    var id_account = value.id_account;
                    var amount = value.amount ;

                    $('div.date input').val(date);
                    $('div.id_account select').val(id_account);
                    $("div.amount input").val(amount);

                });
            }, error : function(){

            },
        });
        
        var action = "{{route('neraca_awal.index')}}/"+id;
        $('#editInitial').attr('action', action);

    });

    function copytextbox() {
        var angka = document.getElementById('harga').value;
        var rupiah = '';		
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            // 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('')
        document.getElementById('hasil').value = "Rp"+rupiah.split('',rupiah.length-1).reverse().join('');
    }

</script>
@include('sweetalert::alert')
@endpush
