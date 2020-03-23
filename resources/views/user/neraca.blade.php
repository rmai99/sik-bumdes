@extends('user/layout/template')

@section('title', 'Neraca')

@section('title-page', 'Neraca')

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
                        <h3 class="title" style="font-weight: 400;">Neraca</h3>
                        <p class=""><strong>Periode</strong> {{ $dt }}</p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="pl-1 padding-select groupbyYear" id="search" style="border-radius: 3px;">
                                            <option value="0" disabled selected>Year</option>
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
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      <td style="width:60%">Asset</td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td style="width:10%"></td>
                                    </tr>
                                    @php
                                      $sum = 0;
                                      $sum_biaya = 0;
                                      $sum_ekuitas = 0;
                                    @endphp
                                    @for ($i = 0; $i < sizeof($array_asset); $i++)
                                      <tr>
                                          <td style="width:60%;padding-left: 1.5rem!important;">
                                            <strong>{{$array_asset[$i]['class']}}</strong>
                                          </td>
                                          <td style="width:15%"></td>
                                          <td style="width:15%"></td>
                                          <td style="width:10%"></td>
                                      </tr>
                                        @if (isset($array_asset[$i]['nama']))
                                          @for ($y = 0; $y < sizeof($array_asset[$i]['nama']); $y++)
                                            @if ($array_asset[$i]['saldo_akhir'][$y] != "0")
                                            <tr>
                                                <td style="width:60%;padding-left: 3rem!important;">
                                                  {{$array_asset[$i]['kode'][$y]}}- {{$array_asset[$i]['nama'][$y]}}
                                                </td>
                                                <td style="width:15%">
                                                    
                                                </td>
                                                <td style="width:15%">
                                                </td>
                                                <td class="text-right" style="width:10%">
                                                  @if ($array_asset[$i]['saldo_akhir'][$y] < 0)
                                                    - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$array_asset[$i]['saldo_akhir'][$y])),3)))}}  
                                                  @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($array_asset[$i]['saldo_akhir'][$y])),3)))}}
                                                  @endif
                                                  @php
                                                      $sum += $array_asset[$i]['saldo_akhir'][$y];
                                                  @endphp
                                                </td>
                                            </tr>
                                            @endif
                                          @endfor
                                        @endif
                                        <tr>
                                          <td style="width:60%;padding-left: 1.5rem!important;">
                                              Total {{$array_asset[$i]['class']}}
                                          </td>
                                          <td style="width:15%"></td>
                                          <td style="width:15%"></td>
                                          <td class="text-right" style="width:10%"></td>
                                        </tr>
                                    @endfor
                                    <tr>
                                      <td style="width:60%">
                                          <strong>Total Asset</strong>
                                      </td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td class="text-right" style="width:10%">
                                        @if ($sum < 0)
                                          - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum)),3)))}}
                                        @else
                                          Rp{{strrev(implode('.',str_split(strrev(strval($sum)),3)))}}
                                        @endif
                                      </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            Liabilitas
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 0; $i < sizeof($array_liability); $i++)
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          <strong>{{$array_liability[$i]['class']}}</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                      </tr>
                                      @if (isset($array_asset[$i]['nama']))
                                        @for ($j = 0; $j < sizeof($array_liability[$i]['saldo_akhir']); $j++)
                                        @if ($array_liability[$i]['saldo_akhir'][$j] != 0)
                                          <tr>
                                            <td style="width:60%;padding-left: 3rem!important;">
                                              {{$array_liability[$i]['kode'][$j]}} - {{$array_liability[$i]['nama'][$j]}}
                                            </td>
                                            <td style="width:15%">
                                                
                                            </td>
                                            <td style="width:15%">
                                            </td>
                                            <td class="text-right" style="width:10%">
                                              @if ($array_liability[$i]['saldo_akhir'][$j] < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$array_liability[$i]['saldo_akhir'][$j])),3)))}}
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($array_liability[$i]['saldo_akhir'][$j])),3)))}}  
                                              @endif
                                              @php
                                                  $sum_biaya += $array_liability[$i]['saldo_akhir'][$j];
                                              @endphp
                                            </td>
                                          </tr>
                                        @endif
                                        @endfor
                                      @endif
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          Total {{$array_liability[$i]['class']}}
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%" class="text-right">
                                            
                                        </td>
                                      </tr>
                                    @endfor
                                    <tr>
                                      <td style="width:60%">
                                          <strong>Total Liabilitas</strong>
                                      </td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td class="text-right" style="width:10%">
                                        <strong>
                                          @if ($sum_biaya < 0)
                                            - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum_biaya)),3)))}}
                                          @else
                                            Rp{{strrev(implode('.',str_split(strrev(strval($sum_biaya)),3)))}}
                                          @endif
                                        </strong>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td style="width:60%">
                                            Ekuitas
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @for ($i = 0; $i < sizeof($array_equity); $i++)
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          <strong>{{$array_equity[$i]['class']}}</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                      </tr>
                                      @if (isset($array_asset[$i]['nama']))
                                        @for ($j = 0; $j < sizeof($array_equity[$i]['saldo_akhir']); $j++)
                                        @if ($array_equity[$i]['saldo_akhir'][$j] != 0)
                                          <tr>
                                            <td style="width:60%;padding-left: 3rem!important;">
                                              {{$array_equity[$i]['kode'][$j]}} - {{$array_equity[$i]['nama'][$j]}}
                                            </td>
                                            <td style="width:15%">
                                                
                                            </td>
                                            <td style="width:15%">
                                            </td>
                                            <td class="text-right" style="width:10%">
                                              @if ($array_equity[$i]['saldo_akhir'][$j] < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$array_equity[$i]['saldo_akhir'][$j])),3)))}}
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($array_equity[$i]['saldo_akhir'][$j])),3)))}}
                                              @endif
                                              @php
                                                  $sum_ekuitas += $array_equity[$i]['saldo_akhir'][$j];
                                              @endphp
                                            </td>
                                          </tr>
                                        @endif
                                        @endfor
                                      @endif
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          Total {{$array_equity[$i]['class']}}
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%" class="text-right"></td>
                                      </tr>
                                    @endfor
                                    <tr>
                                      <td style="width:60%">
                                          <strong>Total Ekuitas</strong>
                                      </td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td class="text-right" style="width:10%">
                                        <strong>
                                          @if ($sum_ekuitas < 0)  
                                            - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum_ekuitas)),3)))}}</strong>
                                          @else
                                            Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas)),3)))}}</strong>
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
    $(document).on('change', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();

        var url = "/neraca?year=" + year;
        window.location.href = url;

    });
</script>
@endpush