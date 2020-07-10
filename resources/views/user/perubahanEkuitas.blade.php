@extends('user/layout/template')

@section('title', 'Laporan Perubahan Ekuitas')

@section('title-page', 'Laporan Perubahan Ekuitas')

@section('content')
<div class="content">
    @php
        if (isset($_GET['year'])) {
            $dt = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $dt = date('Y');
            $month = date('m');
        }
        setlocale(LC_ALL, 'id_ID');
        $modal_awal = 0;
        $prive = 0;
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2 mb-2">
                        <h3 class="title" style="font-weight: 400;">Perubahan Ekuitas</h3>
                        @php
                            $dateObj   = DateTime::createFromFormat('!m', $month);
                            $monthName = $dateObj->format('F'); // March
                        @endphp
                        <p class=""><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-2 pl-md-0 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Tahun</option>
                                            @foreach ($years as $y)
                                              <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}
                                              </option>
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
                                    <a href="{{route('export.perubahan_ekuitas', ['year' => $dt, 'month' => $month])}}" class="btn btn-primary" target="_blank" id="export">Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Modal Awal</strong>
                                        </td>
                                        <td></td>
                                        <td style="width:10%" class="text-right">
                                            @for ($i = 1; $i <= sizeof($equityArray); $i++)
                                                @if ($equityArray[$i]['name'] == "Modal Disetor")
                                                <strong>
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'])),3)))}}
                                                </strong>
                                                    @php
                                                        $modal_awal += $equityArray[$i]['ending balance'];
                                                    @endphp
                                                @endif
                                            @endfor
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                            Laba bersih
                                        </td>
                                        <td style="width:10%" class="text-right">
                                            @if ($saldo_berjalan < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$saldo_berjalan)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan)),3)))}}
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    @for ($i = 1; $i <= sizeof($equityArray); $i++)
                                        @if ($equityArray[$i]['name'] == "Prive")
                                            <tr>
                                                <td style="width:60%;padding-left: 1.5rem!important;">
                                                    {{ $equityArray[$i]['name'] }}
                                                </td>
                                                <td class="text-right" style="width:10%">
                                                    @if ($equityArray[$i]['ending balance'] < 0)
                                                        -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equityArray[$i]['ending balance'])),3)))}}
                                                    @else
                                                        Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'])),3)))}}
                                                    @endif
                                                    @php
                                                        $prive += $equityArray[$i]['ending balance'];
                                                    @endphp
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Total Penambahan Modal</strong>
                                        </td>
                                        <td></td>
                                        <td style="width:10%" class="text-right">
                                            @if ($saldo_berjalan >= 0)
                                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan - $prive)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan + $prive)),3)))}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            <strong  class="text-danger">Modal Akhir</strong>
                                        </td>
                                        <td></td>
                                        <td class="text-right" style="width:10%">
                                            @if ($saldo_berjalan >= 0)
                                                Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal + $saldo_berjalan - $prive)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal - $saldo_berjalan + $prive)),3)))}}
                                            @endif
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
    $(document).ready(function () {
        $('#datatables').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari",
            }
        });
    });
    $(document).on('click', '#search', function (e) {
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();

        var url = "{{route('perubahan_ekuitas')}}?year=" + year;
        if (month != null) {
            url = url + "&month=" + month;
            console.log('month');
        } else if(month == 0){
            url = url;
        }
        window.location.href = url;

    })
</script>
@endpush
