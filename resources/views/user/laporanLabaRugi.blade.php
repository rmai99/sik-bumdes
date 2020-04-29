@extends('user/layout/template')

@section('title', 'Laporan Laba Rugi')

@section('title-page', 'Laporan Laba Rugi')

@section('content')
    @php
    if (isset($_GET['year'])) {
        $dt = $_GET['year'];
    } else {
        $dt = date('Y');
    }

    @endphp
    
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2 mb-2">
                        <h3 class="title" style="font-weight: 400;">Laba Rugi</h3>
                        <p class=""><strong>Periode</strong> {{ $dt }}</p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="pl-1 padding-select groupbyYear" style="border-radius: 3px;"
                                            id="search">
                                            <option value="0" disabled="true" selected="true">Year</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}</option>
                                            @endforeach
                                        </select>
                                        <b class="caret"></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:60%">
                                            Pendapatan
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                        @php
                                            $sum_biaya = 0;
                                            $another_sum = 0;
                                            $another_biaya = 0;
                                        @endphp
                                    @for ($i = 0; $i < sizeof($array); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$array[$i]['class']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td style="width:10%"></td>
                                        </tr>
                                        @if (isset($array[$i]['nama']))
                                            @for ($y = 0; $y < sizeof($array[$i]['nama']); $y++) 
                                                @if($array[$i]['saldo_akhir'][$y] !="0" )
                                                    <tr>
                                                        <td style="width:60%;padding-left: 3rem!important;">
                                                            {{$array[$i]['kode'][$y]}}- {{$array[$i]['nama'][$y]}}
                                                        </td>
                                                        <td style="width:15%">
                                                        </td>
                                                        <td style="width:10%">
                                                        </td>
                                                        <td class="text-right" style="width:10%">
                                                            @if ($array[$i]['saldo_akhir'][$y] < 0)
                                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$array[$i]['saldo_akhir'][$y])),3)))}}
                                                            @else
                                                                Rp{{strrev(implode('.',str_split(strrev(strval($array[$i]['saldo_akhir'][$y])),3)))}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endif
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                Total {{$array[$i]['class']}}
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td class="text-right" style="width:10%"></td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Total Pendapatan</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            Rp{{strrev(implode('.',str_split(strrev(strval($pendapatan)),3)))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            Biaya
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 0; $i < sizeof($bebanArray); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$bebanArray[$i]['class']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td style="width:10%"></td>
                                        </tr>
                                        @if (isset($array[$i]['nama']))
                                            @for ($j = 0; $j < sizeof($bebanArray[$i]['saldo_akhir']); $j++)
                                                @if($bebanArray[$i]['saldo_akhir'][$j] !=0)
                                                    <tr>
                                                        <td style="width:60%;padding-left: 3rem!important;">
                                                            {{$bebanArray[$i]['kode'][$j]}} -
                                                            {{$bebanArray[$i]['nama'][$j]}}
                                                        </td>
                                                        <td style="width:15%"></td>
                                                        <td style="width:15%"></td>
                                                        <td class="text-right" style="width:10%">
                                                            Rp{{strrev(implode('.',str_split(strrev(strval($bebanArray[$i]['saldo_akhir'][$j])),3)))}}
                                                            @php
                                                                $sum_biaya += $bebanArray[$i]['saldo_akhir'][$j];
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endif
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                Total {{ $bebanArray[$i]['class'] }}
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td style="width:10%" class="text-right"></td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Total Biaya</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            @if ($sum_biaya < 0)
                                                <strong>-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum_biaya)),3)))}}</strong>
                                            @else
                                                <strong>Rp{{strrev(implode('.',str_split(strrev(strval($sum_biaya)),3)))}}</strong>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            <strong class="text-danger">Laba Usaha</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            <strong class="text-danger">
                                                @if (($pendapatan - $sum_biaya) < 0)
                                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($pendapatan - $sum_biaya))),3)))}}
                                                @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($pendapatan - $sum_biaya)),3)))}}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            Pendapatan dan Biaya Lainnya
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 0; $i < sizeof($array_pendapatan_lainnya); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$array_pendapatan_lainnya[$i]['class']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td class="text-right" style="width:10%">
                                                {{$array_pendapatan_lainnya[$i]['sum']}}
                                                @php
                                                    $pendapatan += $array_pendapatan_lainnya[$i]['sum'];
                                                    $another_sum += $array_pendapatan_lainnya[$i]['sum'];
                                                @endphp
                                            </td>
                                        </tr>
                                    @endfor
                                    @for ($i = 0; $i < sizeof($array_biaya_lainnya); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$array_biaya_lainnya[$i]['class']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td class="text-right" style="width:10%">
                                                {{$array_biaya_lainnya[$i]['sum']}}
                                                @php
                                                    $sum_biaya += $array_biaya_lainnya[$i]['sum'];
                                                    $another_biaya += $array_biaya_lainnya[$i]['sum'];
                                                @endphp
                                            </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Total Pendapatan dan Biaya Lainnya</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">{{ $another_sum - $another_biaya}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            <strong>SALDO LABA/RUGI TAHUN BERJALAN</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            @if (($pendapatan - $sum_biaya) < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($pendapatan - $sum_biaya))),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($pendapatan - $sum_biaya)),3)))}}
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
    $(document).on('change', '#search', function (e) {
        e.preventDefault();
        var year = $("select.groupbyYear").val();

        var url = "/laporan_laba_rugi?year=" + year;
        window.location.href = url;

    })
</script>
@endpush
