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
                                    @for ($i = 0; $i < sizeof($incomeArray); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$incomeArray[$i]['classification']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td style="width:10%"></td>
                                        </tr>
                                        @if (isset($incomeArray[$i]['name']))
                                            @for ($y = 0; $y < sizeof($incomeArray[$i]['name']); $y++) 
                                                @if($incomeArray[$i]['ending balance'][$y] !="0" )
                                                    <tr>
                                                        <td style="width:60%;padding-left: 3rem!important;">
                                                            {{$incomeArray[$i]['code'][$y]}}- {{$incomeArray[$i]['name'][$y]}}
                                                        </td>
                                                        <td style="width:15%">
                                                        </td>
                                                        <td style="width:10%">
                                                        </td>
                                                        <td class="text-right" style="width:10%">
                                                            @if ($incomeArray[$i]['ending balance'][$y] < 0)
                                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$incomeArray[$i]['ending balance'][$y])),3)))}}
                                                            @else
                                                                Rp{{strrev(implode('.',str_split(strrev(strval($incomeArray[$i]['ending balance'][$y])),3)))}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endif
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                Total {{$incomeArray[$i]['classification']}}
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
                                            Rp{{strrev(implode('.',str_split(strrev(strval($income)),3)))}}
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
                                    @for ($i = 0; $i < sizeof($expenseArray); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$expenseArray[$i]['classification']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td style="width:10%"></td>
                                        </tr>
                                        @if (isset($incomeArray[$i]['name']))
                                            @for ($j = 0; $j < sizeof($expenseArray[$i]['ending balance']); $j++)
                                                @if($expenseArray[$i]['ending balance'][$j] !=0)
                                                    <tr>
                                                        <td style="width:60%;padding-left: 3rem!important;">
                                                            {{$expenseArray[$i]['code'][$j]}} -
                                                            {{$expenseArray[$i]['name'][$j]}}
                                                        </td>
                                                        <td style="width:15%"></td>
                                                        <td style="width:15%"></td>
                                                        <td class="text-right" style="width:10%">
                                                            Rp{{strrev(implode('.',str_split(strrev(strval($expenseArray[$i]['ending balance'][$j])),3)))}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endif
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                Total {{ $expenseArray[$i]['classification'] }}
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
                                            @if ($expense < 0)
                                                <strong>-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$expense)),3)))}}</strong>
                                            @else
                                                <strong>Rp{{strrev(implode('.',str_split(strrev(strval($expense)),3)))}}</strong>
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
                                                @if (($income - $expense) < 0)
                                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($income - $expense))),3)))}}
                                                @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($income - $expense)),3)))}}
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
                                    @for ($i = 0; $i < sizeof($othersIncomeArray); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$othersIncomeArray[$i]['classification']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td class="text-right" style="width:10%">
                                                {{$othersIncome}}
                                            </td>
                                        </tr>
                                    @endfor
                                    @for ($i = 0; $i < sizeof($othersExpenseArray); $i++)
                                        <tr>
                                            <td style="width:60%;padding-left: 1.5rem!important;">
                                                <strong>{{$othersExpenseArray[$i]['classification']}}</strong>
                                            </td>
                                            <td style="width:15%"></td>
                                            <td style="width:15%"></td>
                                            <td class="text-right" style="width:10%">
                                                {{$othersExpense}}
                                            </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td style="width:60%">
                                            <strong>Total Pendapatan dan Biaya Lainnya</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">{{ $othersIncome - $othersExpense}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            <strong>SALDO LABA/RUGI TAHUN BERJALAN</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td class="text-right" style="width:10%">
                                            @if (($income + $othersIncome - $expense - $othersExpense) < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($income + $othersIncome - $expense - $othersExpense))),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($income + $othersIncome - $expense - $othersExpense)),3)))}}
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
