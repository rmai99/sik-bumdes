@extends('user/layout/template')

@section('title', 'Neraca Saldo')

@section('title-page', 'Neraca Saldo')

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
                        <h3 class="title" style="font-weight: 400;">Neraca Saldo</h3>
                        <p class=""><strong>Periode</strong> {{ $dt }} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun :</strong>
                                        <select class="pl-1 padding-select groupbyYear" id="search"
                                            style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Year</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}</option>
                                            @endforeach
                                        </select>
                                        <b class="caret"></b>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 pr-0">
                                    <button type="button" class="btn btn-primary m-1 pl-2 pr-2" data-toggle="modal"
                                        data-target="#neracaAwalModal" style="float:right;">Tambah Neraca Awal</button>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width:40%">Nama Akun</th>
                                        <th rowspan="2" style="width:10%">Posisi Normal</th>
                                        <th colspan="2" class="text-center">Jumlah</th>
                                    </tr>
                                    <tr>
                                        <th style="width:20%">Debit</th>
                                        <th style="width:20%">Kredit</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                @for ($i = 0; $i < sizeof($save); $i++)
                                    <tr data-toggle="collapse" class="accordion-toggle">
                                        <td>
                                            <strong>{{$save[$i]['parent_id']}} - {{$save[$i]['parent_name']}}</strong>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @for ($j = 0; $j < sizeof($save[$i]['classification']); $j++)
                                        <tr class="accordian-body collapse accordion-toggle show data{{$save[$i]['parent_id']}}"
                                            data-toggle="collapse" data-target=".dataAkun">
                                            <td style="padding-left: 1.5rem!important;">
                                                {{$save[$i]['classification'][$j]['classification_name']}}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @for ($k = 0; $k < sizeof($save[$i]['classification'][$j]['account']); $k++)
                                            @if ($save[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != "0")
                                                <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun">
                                                    <td style="padding-left: 3rem!important;">
                                                        
                                                        {{ $save[$i]['classification'][$j]['account'][$k]['account_code'] }} - {{ $save[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $save[$i]['classification'][$j]['account'][$k]['position'] }}
                                                    </td>
                                                    <td>
                                                        @if ($save[$i]['classification'][$j]['account'][$k]['position'] == "Debit")
                                                            @if ($save[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$save[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                            @else
                                                                Rp{{strrev(implode('.',str_split(strrev(strval($save[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($save[$i]['classification'][$j]['account'][$k]['position'] == "Kredit")
                                                            @if ($save[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$save[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                            @else 
                                                                Rp{{strrev(implode('.',str_split(strrev(strval($save[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    @endfor
                                @endfor
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
    $(document).on('change', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();

        var url = "{{route('neraca_saldo.index')}}?year=" + year;
        window.location.href = url;

    });
    $(document).ready(function () {
        $('#datatables').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            responsive: true,
            language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari",
            }
        });
    });
</script>
@endpush