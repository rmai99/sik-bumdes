@extends('user/layout/template')

@section('title', 'Buku Besar')

@section('title-page', 'Buku Besar')

@section('content')
@php
    if(isset($_GET['year'], $_GET['akun'])){
        $year = $_GET['year'];
        $akun = $_GET['akun'];
    } else {
        $year = date('Y');
        $akun = $account;
    }

    $saldo_awal = $log['saldo_awal'];
    $saldo_akhir = $log['saldo_awal'];
    $debit = 0;
    $kredit = 0;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Buku Besar</h3>
                        <p class=""><strong>Periode</strong> {{$year}} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-1 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun</strong>
                                        <select class="pl-1 padding-select groupbyYear" id="" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Year</option>
                                            @foreach ($years as $y)
                                                <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 pl-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Akun</strong>
                                        <select class="w-100 pl-1 padding-select groupbyAccount" id="" style="border-radius: 3px;">
                                            <option value="0" selected disabled>Akun</option>
                                            @foreach ($akuns as $a)
                                                <option value="{{ $a->id }}" {{ $akun == $a->id ? 'selected' : '' }}>{{ $a->account_code }} - {{ $a->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <button type="button" class="btn btn-primary" id="search">Cari</button>
                                </div>  
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="row d-flex">
                                        <div class="col-md-6 col-sm-3">
                                            <p class="mb-0">Nama Akun</p>
                                        </div>
                                        <div class="col-md-6 col-sm-3">
                                            <p class="mb-0"><strong> : {{$log['nama_akun']}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div class="col-lg-6">
                                            <p class="mb-0">Kode Akun</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p class="mb-0"><strong> : {{$log['kode_akun']}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div class="col-lg-6">
                                            <p class="mb-0">Posisi Normal</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p class="mb-0"><strong> : {{$log['position']}}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row d-flex">
                                        <div class="col-lg-6 col-md-3">
                                            <p class="mb-0">Saldo Awal</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p class="mb-0"><strong> : Rp{{strrev(implode('.',str_split(strrev(strval($saldo_awal)),3)))}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div class="col-lg-6 col-md-3">
                                            <p class="mb-0">Saldo Akhir</p>
                                        </div>
                                        <div class="col-lg-6 col-md-3">
                                            <p class="mb-0"> : <strong id="saldo_akhir"></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="generalLedger" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2" style="width:15%">Tanggal</th>
                                        <th rowspan="2" style="width:40%">Keterangan</th>
                                        <th colspan="2" class="text-center">Nama Akun</th>
                                        <th rowspan="2">Saldo</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ strftime("%d %B %G", strtotime($log['date'])) }} </td>
                                        <td>Saldo Awal</td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            Rp{{strrev(implode('.',str_split(strrev(strval($log['saldo_awal'])),3)))}}
                                        </td>
                                    </tr>
                                    @foreach ($data as $d)
                                    <tr>
                                        <td>{{ strftime("%d %B %G", strtotime($d->detail->date)) }}</td>
                                        <td>{{ $d->detail->description }}</td>
                                        <td>
                                            @if ($d->position=="Debit")
                                                @if ($d->amount < 0)
                                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$d->amount)),3)))}}
                                                @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($d->amount)),3)))}}
                                                @endif
                                                @php
                                                    $debit += $d->amount;
                                                @endphp
                                            @endif
                                        </td>
                                        <td>
                                            @if ($d->position == "Kredit")
                                                @if ($d->amount < 0)
                                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$d->amount)),3)))}}
                                                @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($d->amount)),3)))}}
                                                @endif
                                                @php
                                                    $kredit += $d->amount;
                                                @endphp
                                            @endif
                                        </td>
                                        <td>
                                            @if ($log['position'] == "Debit")
                                                @if ($d->position == "Kredit")
                                                    @php
                                                        $saldo_akhir -= $d->amount;        
                                                    @endphp
                                                @elseif ($d->position == "Debit")
                                                    @php
                                                        $saldo_akhir += $d->amount;        
                                                    @endphp
                                                @endif
                                            @elseif ($log['position'] == "Kredit")
                                                @if ($d->position == "Kredit")
                                                    @php
                                                        $saldo_akhir += $d->amount;        
                                                    @endphp
                                                @elseif ($d->position == "Debit")
                                                    @php
                                                        $saldo_akhir -= $d->amount;
                                                    @endphp
                                                @endif
                                            @endif
                                            @if ($saldo_akhir < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$saldo_akhir)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_akhir)),3)))}}
                                            @endif
                                        </td>
                                    </tr>
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

@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#generalLedger').DataTable({
            "paging": false,
            "ordering": false,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari",
            }
        });
        var table = $('#generalLedger').DataTable();
        var test = table.row( ':last-child' ).data();
        $('#saldo_akhir').text(test[4]);
    });

    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var akun = $("select.groupbyAccount").val();

        var url = "{{route('buku_besar.index')}}?year=" + year + "&akun=" +akun;
        window.location.href = url;

    })
</script>
@endpush