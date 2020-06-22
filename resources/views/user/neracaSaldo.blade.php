@extends('user/layout/template')

@section('title', 'Neraca Saldo')

@section('title-page', 'Neraca Saldo')

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

    $jumlah_debit = 0;
    $jumlah_kredit = 0;

@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Neraca Saldo</h3>
                        @php
                            $dateObj   = DateTime::createFromFormat('!m', $month);
                            $monthName = $dateObj->format('F'); // March
                        @endphp
                        <p class=""><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-2 pl-md-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun :</strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear"
                                            style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Tahun</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
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
                                <div class="col-md-6 mt-4 text-right">
                                    <a href="{{route('export.neraca_saldo', ['year' => $dt, 'month' => $month])}}" class="btn btn-primary" target="_blank" id="export">Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:40%">Nama Akun</th>
                                        <th style="width:10%">Posisi Normal</th>
                                        <th style="width:20%">Debit</th>
                                        <th style="width:20%">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @for ($i = 0; $i < sizeof($balance); $i++)
                                    <tr>
                                        <td>
                                            <strong>{{$balance[$i]['parent_code']}} - {{$balance[$i]['parent_name']}}</strong>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if (isset($balance[$i]['classification']))
                                        @for ($j = 0; $j < sizeof($balance[$i]['classification']); $j++)
                                            <tr>
                                                <td style="padding-left: 1.5rem!important;">
                                                    {{$balance[$i]['classification'][$j]['classification_name']}}
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @if (isset($balance[$i]['classification'][$j]['account']))
                                                @for ($k = 0; $k < sizeof($balance[$i]['classification'][$j]['account']); $k++)
                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != "0")
                                                        <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun">
                                                            <td style="padding-left: 3rem!important;">
                                                                {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }} - {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                                            </td>
                                                            <td>
                                                                {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                                            </td>
                                                            <td>
                                                                @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Debit")
                                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                                        -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                                    @else
                                                                        Rp{{strrev(implode('.',str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                                    @endif
                                                                    @php
                                                                        $jumlah_debit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                                    @endphp
                                                                @endif
                                                                
                                                            </td>
                                                            <td>
                                                                @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Kredit")
                                                                    @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                                        - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                                    @else 
                                                                        Rp{{strrev(implode('.',str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                                    @endif
                                                                    @php
                                                                        $jumlah_kredit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                                    @endphp
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @endif
                                        @endfor
                                    @endif
                                @endfor
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong></strong></td>
                                    <td>
                                        <strong>
                                            @if ($jumlah_debit < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$jumlah_debit)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_debit)),3)))}}
                                            @endif
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>
                                            @if ($jumlah_kredit < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$jumlah_kredit)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_kredit)),3)))}}
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
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
    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();
        console.log(year);

        var url = "{{route('neraca_saldo.index')}}?year=" + year;
        if (month != null) {
            url = url + "&month=" + month;
            console.log('month');
        }
        window.location.href = url;

    });
    $(document).ready(function () {
        $('#datatables').DataTable({
            "ordering": false,
            "info":     false,
            "bPaginate": false,
            responsive: true,
            language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari",
            }
        });
    });
</script>
@endpush