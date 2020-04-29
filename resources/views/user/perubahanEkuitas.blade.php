@extends('user/layout/template')

@section('title', 'Laporan Perubahan Ekuitas')

@section('title-page', 'Laporan Perubahan Ekuitas')

@section('content')
<div class="content">
    @php
        if (isset($_GET['year'])) {
            $dt = $_GET['year'];
        } else {
            $dt = date('Y');
        }

        $modal_awal = 0;
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2 mb-2">
                        <h3 class="title" style="font-weight: 400;">Perubahan Ekuitas</h3>
                        <p class=""><strong>Periode</strong> {{ $dt }} </p>
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
                                                {{$y->year}}
                                              </option>
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
                                            <strong>LABA DITAHAN PERIODE SEBELUMNYA</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 1; $i <= sizeof($array); $i++)
                                        @if ($array[$i]['nama'] != "Modal Disetor" && $array[$i]['nama'] != "Modal Usaha")
                                            <tr>
                                                <td style="width:60%;">
                                                    {{ $array[$i]['kode'] }} - {{ $array[$i]['nama'] }}
                                                </td>
                                                <td style="width:15%"></td>
                                                <td style="width:15%"></td>
                                                <td class="text-right" style="width:10%">
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($array[$i]['saldo_akhir'])),3)))}}
                                                    @php
                                                        $modal_awal += $array[$i]['saldo_akhir'];
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endif
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>PENAMBAHAN/PENGURANGAN LABA DITAHAN</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 1; $i <= sizeof($array); $i++)
                                        @if ($array[$i]['nama'] == "Saldo Laba Tahun Berjalan" || $array[$i]['nama'] == "Laba Ditahan")
                                            <tr>
                                                <td style="width:60%;">
                                                    {{ $array[$i]['kode'] }} - {{ $array[$i]['nama'] }}
                                                </td>
                                                <td style="width:15%"></td>
                                                <td style="width:15%"></td>
                                                <td class="text-right" style="width:10%">
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan)),3)))}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong  class="text-danger">TOTAL EKUITAS AKHIR PERIODE</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal + $saldo_berjalan)),3)))}}
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

        var url = "/perubahan_ekuitas?year=" + year;
        window.location.href = url;

    })
</script>
@endpush
