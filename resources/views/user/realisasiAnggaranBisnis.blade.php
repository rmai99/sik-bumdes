@extends('user/layout/template')

@section('title', 'Laporan Rencana Anggaran')

@section('title-page', 'Laporan Rencana Anggaran')

@section('content')
@php
    if (isset($_GET['year'])) {
        $dt = $_GET['year'];
        $month = $_GET['month'];
    } else {
        $dt = date('Y');
        $month = date('m');
    }
    setlocale(LC_ALL, 'id_ID');
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Realisasi Anggaran Bisnis</h3>
                        <p class=""><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{$dt}} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-md-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun :</strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear"
                                            style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Tahun</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $dt == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}</option>
                                            @endforeach
                                        </select>
                                        <b class="caret"></b>
                                    </div>
                                </div>
                                <div class="col-md-2 pl-md-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Bulan</strong>
                                        <select class="w-100 pl-1 padding-select groupbyMonth" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Bulan</option>
                                            <option value="01" {{ $month == '01' ? 'selected' : '' }}>Januari</option>
                                            <option value="02" {{ $month == '02' ? 'selected' : '' }}>Februari</option>
                                            <option value="03" {{ $month == '03' ? 'selected' : '' }}>Maret</option>
                                            <option value="04" {{ $month == '04' ? 'selected' : '' }}>April</option>
                                            <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mei</option>
                                            <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juni</option>
                                            <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juli</option>
                                            <option value="08" {{ $month == '08' ? 'selected' : '' }}>Agustus</option>
                                            <option value="09" {{ $month == '09' ? 'selected' : '' }}>September</option>
                                            <option value="10" {{ $month == '10' ? 'selected' : '' }}>Oktober</option>
                                            <option value="11" {{ $month == '11' ? 'selected' : '' }}>November</option>
                                            <option value="12" {{ $month == '12' ? 'selected' : '' }}>Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <button type="button" class="btn btn-primary" id="search">Cari</button>
                                </div>
                                <div class="col-lg-6 mt-4 pr-0 pl-0">
                                    <a href="{{route('realisasi.create', ['year' => $dt, 'month' => $month])}}" class="btn btn-primary m-1 pl-2 pr-2" style="float:right;">Tambah Realisasi</a>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th>Anggaran</th>
                                        <th>Realisasi</th>
                                        <th class="text-center">%</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:40%">
                                            <strong> Penerimaan</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                    </tr>
                                    @foreach ($account_plan as $item)
                                        <tr>
                                            <td style="width:40%;padding-left: 1.5rem!important;">
                                                <strong>{{$item->name}}</strong>
                                            </td>
                                            <td style="width:10%"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach ($item->budget_account as $ba)
                                        @if($ba->budget_plan !=null)
                                        <tr>
                                            <td style="width:40%;padding-left: 3rem!important;">
                                                {{$ba->name}}
                                            </td>
                                            <td style="width:15%">
                                                Rp{{strrev(implode('.',str_split(strrev(strval($ba->budget_plan->amount)),3)))}}
                                            </td>
                                            @if ($ba->budget_plan->realization !=null)
                                                <td>
                                                    {{$ba->budget_plan->realization->amount}}
                                                </td>
                                                <td class="text-center">{{$ba->budget_plan->amount/$ba->budget_plan->realization->amount}}</td>
                                                <td>
                                                    <button type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editRealisasiModal" class="edit btn-icon"
                                                        id="{{$ba->budget_plan->id }}">
                                                        <i class="material-icons"
                                                            style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn-icon remove" id="{{ $ba->budget_plan->id }}">
                                                        <i class="material-icons"style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                                    </button>
                                                </td>
                                            @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @endif
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td style="width:40%">
                                            <strong>Belanja</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td></td>
                                        <td style="width:10%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @foreach ($type as $item)
                                    @if ($item->budget_plan != null)
                                        <tr>
                                            <td style="width:40%;padding-left: 3rem!important;">
                                                {{$item->name}}
                                            </td>
                                            <td style="width:15%">
                                                Rp{{strrev(implode('.',str_split(strrev(strval($item->budget_plan->amount)),3)))}}
                                            </td>
                                            @if ($item->budget_plan->realization !=null)
                                                <td>
                                                    {{$item->budget_plan->realization->amount}}
                                                </td>
                                                <td class="text-center">{{$item->budget_plan->amount/$item->budget_plan->realization->amount}}</td>
                                                <td>
                                                    <button type="button" rel="tooltip" title="Edit Akun" data-toggle="modal" data-target="#editRealisasiModal" class="edit btn-icon"
                                                        id="{{ $item->budget_plan->id }}">
                                                        <i class="material-icons"
                                                            style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                                    </button>
                                                    <button type="button" rel="tooltip" class="btn-icon remove" id="{{ $item->budget_plan->id }}">
                                                        <i class="material-icons"style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                                    </button>
                                                </td>
                                            @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @endif
                                        </tr>
                                    @endif
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
<div class="modal fade" id="editRealisasiModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Edit Realisasi</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="editRealisasi">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="id_plan" id="id_realisasi">

                            <div class="form-group account">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <input type="text" class="form-control" data-type="currency" disabled>
                            </div>

                            <div class="form-group budget">
                                <h6 class="text-dark font-weight-bold m-0">Anggaran</h6>
                                <input type="text" class="form-control" disabled>
                            </div>

                            <div class="form-group realisasi">
                                <h6 class="text-dark font-weight-bold m-0">Realisasi</h6>
                                <input type="text" class="form-control" name="realisasi" data-type="currency">
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
    $(document).on('click', 'select', function(e) {
        $('select').chosen();
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#id_realisasi').val(id);
        console.log(id);
        $.ajax({
            type        : 'GET',
            url         : '{!!url('detail_realisasi')!!}',
            data        : {'id':id},
            dataType    : 'html',
            success     : function(data){
                var servers = $.parseJSON(data);
                console.log(servers);
                $.each(servers, function(index, value){
                    var account = value.budget_account.name;
                    var rupiah = '';
                    var convert = value.amount.toString().split('').reverse().join('');
                    for(var i = 0; i < convert.length; i++) if(i%3 == 0) rupiah += convert.substr(i,3)+',';
                    var amount = 'Rp'+ rupiah.split('',rupiah.length-1).reverse().join('');
                    var rupiah = '';

                    var realisasi = value.realization.amount.toString().split('').reverse().join('');
                    for(var i = 0; i < realisasi.length; i++) if(i%3 == 0) rupiah += realisasi.substr(i,3)+',';
                    var realisasi_rupiah = 'Rp'+ rupiah.split('',rupiah.length-1).reverse().join('');

                    $('div.account input').val(account);
                    $('div.budget input').val(amount);
                    $("div.realisasi input").val(realisasi_rupiah);

                });
            }, error : function(){

            },
        });
        
        var action = "{{route('realisasi.update')}}";
        $('#editRealisasi').attr('action', action);

    });
    $(document).on('click', '.remove', function(e) {   
        e.preventDefault();
        var id = $(this).attr('id');
        
        console.log(id);
        var url = "{{ route('realisasi.show') }}/"+id;
        Swal.fire({
            title : 'Anda yakin menghapus realisasi anggaran?',
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
                            'Realisasi Anggaran telah dihapus.',
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


    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();

        var url = "{{route('realisasi.show')}}?year=" + year + "&month=" + month;
        window.location.href = url;

    });
</script>
<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        click : function(){
            formatCurrency($(this));
        }
    });

    function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.
    
    // get input value
    var input_val = input.val();
    
    // don't validate empty input
    if (input_val === "") { return; }
    
    // original length
    var original_len = input_val.length;

    // initial caret position 
    var caret_pos = input.prop("selectionStart");
        
    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);
        
        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "Rp" + left_side + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        // console.log('input_val', input_val)
        input_val = formatNumber(input_val);
        input_val = "Rp" + input_val;
        
    }
    
    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>
@include('sweetalert::alert')
@endpush
