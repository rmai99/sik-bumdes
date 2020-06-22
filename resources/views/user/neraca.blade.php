@extends('user/layout/template')

@section('title', 'Neraca')

@section('title-page', 'Neraca')

@section('content')
@php
  if (isset($_GET['year'], $_GET['month'])) {
      $dt = $_GET['year'];
      $month = $_GET['month'];
  } else {
      $dt = date('Y');
      $month = date('m');
  }
  setlocale(LC_ALL, 'id_ID');
@endphp

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2 mb-2">
                        <h3 class="title" style="font-weight: 400;">Neraca</h3>
                        @php
                            $dateObj   = DateTime::createFromFormat('!m', $month);
                            $monthName = $dateObj->format('F'); // March
                        @endphp
                        <p class=""><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-2 pl-md-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear" style="border-radius: 3px;">
                                            <option value="0" disabled selected>Tahun</option>
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
                                          <option disabled="true" selected="true">Bulan</option>
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
                                <a href="{{route('export.neraca', ['year' => $dt, 'month' => $month])}}" class="btn btn-primary" target="_blank" id="export">Export</a>
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
                                    @for ($i = 0; $i < sizeof($assetArray); $i++)
                                      <tr>
                                          <td style="width:60%;padding-left: 1.5rem!important;">
                                            <strong>{{$assetArray[$i]['classification']}}</strong>
                                          </td>
                                          <td style="width:15%"></td>
                                          <td style="width:15%"></td>
                                          <td style="width:10%"></td>
                                      </tr>
                                        @if (isset($assetArray[$i]['name']))
                                          @for ($y = 0; $y < sizeof($assetArray[$i]['name']); $y++)
                                            @if ($assetArray[$i]['ending balance'][$y] != "0")
                                            <tr>
                                                <td style="width:60%;padding-left: 3rem!important;">
                                                  {{$assetArray[$i]['code'][$y]}}- {{$assetArray[$i]['name'][$y]}}
                                                </td>
                                                <td style="width:15%">
                                                    
                                                </td>
                                                <td style="width:15%">
                                                </td>
                                                <td class="text-right" style="width:10%">
                                                  @if ($assetArray[$i]['ending balance'][$y] < 0)
                                                    - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$assetArray[$i]['ending balance'][$y])),3)))}}  
                                                  @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($assetArray[$i]['ending balance'][$y])),3)))}}
                                                  @endif
                                                </td>
                                            </tr>
                                            @endif
                                          @endfor
                                        @endif
                                        <tr>
                                          <td style="width:60%;padding-left: 1.5rem!important;">
                                              <b>Total {{$assetArray[$i]['classification']}}</b>
                                          </td>
                                          <td style="width:15%"></td>
                                          <td style="width:15%"></td>
                                          <td class="text-right" style="width:10%">
                                            <b>
                                              {{--  {{$assetArray[$i]['sum']}}  --}}
                                              @if ($assetArray[$i]['sum'] < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$assetArray[$i]['sum'])),3)))}}  
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($assetArray[$i]['sum'])),3)))}}
                                              @endif
                                              @php
                                                  $sum += $assetArray[$i]['sum'];
                                              @endphp
                                            </b>
                                          </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                      <td style="width:60%">
                                          <strong>Total Asset</strong>
                                      </td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td class="text-right" style="width:10%">
                                        <strong>
                                          @if ($sum < 0)
                                          - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum)),3)))}}
                                          @else
                                            Rp{{strrev(implode('.',str_split(strrev(strval($sum)),3)))}}
                                          @endif
                                        </strong>
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
                                    @for ($i = 0; $i < sizeof($liabilityArray); $i++)
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          <strong>{{$liabilityArray[$i]['classification']}}</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                      </tr>
                                      @if (isset($assetArray[$i]['name']))
                                        @for ($j = 0; $j < sizeof($liabilityArray[$i]['ending balance']); $j++)
                                        @if ($liabilityArray[$i]['ending balance'][$j] != 0)
                                          <tr>
                                            <td style="width:60%;padding-left: 3rem!important;">
                                              {{$liabilityArray[$i]['code'][$j]}} - {{$liabilityArray[$i]['name'][$j]}}
                                            </td>
                                            <td style="width:15%">
                                                
                                            </td>
                                            <td style="width:15%">
                                            </td>
                                            <td class="text-right" style="width:10%">
                                              @if ($liabilityArray[$i]['ending balance'][$j] < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$liabilityArray[$i]['ending balance'][$j])),3)))}}
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($liabilityArray[$i]['ending balance'][$j])),3)))}}  
                                              @endif
                                              @php
                                                  $sum_biaya += $liabilityArray[$i]['ending balance'][$j];
                                              @endphp
                                            </td>
                                          </tr>
                                        @endif
                                        @endfor
                                      @endif
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          <b>Total {{$liabilityArray[$i]['classification']}}</b>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%" class="text-right">
                                          </b>
                                            @if ($liabilityArray[$i]['sum'] < 0)
                                              - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$liabilityArray[$i]['sum'])),3)))}}
                                            @else
                                              Rp{{strrev(implode('.',str_split(strrev(strval($liabilityArray[$i]['sum'])),3)))}}  
                                            @endif
                                          </b>
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
                                    @for ($i = 0; $i < sizeof($equityArray); $i++)
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          <strong>{{$equityArray[$i]['classification']}}</strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                      </tr>
                                      @if (isset($assetArray[$i]['name']))
                                        @for ($j = 0; $j < sizeof($equityArray[$i]['ending balance']); $j++)
                                        @if ($equityArray[$i]['name'][$j] != "Saldo Laba Ditahan")
                                          <tr>
                                            <td style="width:60%;padding-left: 3rem!important;">
                                              {{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
                                            </td>
                                            <td style="width:15%">
                                                
                                            </td>
                                            <td style="width:15%">
                                            </td>
                                            <td class="text-right" style="width:10%">
                                              @if ($equityArray[$i]['ending balance'][$j] < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equityArray[$i]['ending balance'][$j])),3)))}}
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'][$j])),3)))}}
                                              @endif
                                              @php
                                                  $sum_ekuitas += $equityArray[$i]['ending balance'][$j];
                                              @endphp
                                            </td>
                                          </tr>
                                        @endif
                                        @if ($equityArray[$i]['name'][$j] == "Saldo Laba Ditahan")
                                          <tr>
                                            <td style="width:60%;padding-left: 3rem!important;">
                                              {{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
                                            </td>
                                            <td style="width:15%">
                                                
                                            </td>
                                            <td style="width:15%">
                                            </td>
                                            <td class="text-right" style="width:10%">
                                              @if ($equitas < 0)
                                                - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equitas)),3)))}}
                                              @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($equitas)),3)))}}
                                              @endif
                                              @php
                                                  $sum_ekuitas += $equitas;
                                              @endphp
                                            </td>
                                          </tr>
                                        @endif
                                        @endfor
                                      @endif
                                      <tr>
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                          Total {{$equityArray[$i]['classification']}}
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
                                    <tr>
                                      <td style="width:60%">
                                          <strong>Total Liabilitas dan Ekuitas</strong>
                                      </td>
                                      <td style="width:15%"></td>
                                      <td style="width:15%"></td>
                                      <td class="text-right" style="width:10%">
                                        <strong>
                                          @if ($sum_ekuitas+$sum_biaya < 0)  
                                            - Rp{{strrev(implode('.',str_split(strrev(strval(-1*($sum_ekuitas+$sum_biaya))),3)))}}</strong>
                                          @else
                                            Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas+$sum_biaya)),3)))}}</strong>
                                          @endif
                                        </strong>
                                      </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                              <div class="col-lg-6 text-center">
                                Total Aset
                                <br>
                                <strong>
                                  @if ($sum < 0)
                                  - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum)),3)))}}
                                  @else
                                    Rp{{strrev(implode('.',str_split(strrev(strval($sum)),3)))}}
                                  @endif
                                </strong>
                              </div>
                              <div class="col-lg-6 text-center">
                                Total Liabilitas dan Ekuitas
                                <br>
                                <strong>
                                  @if ($sum_ekuitas+$sum_biaya < 0)  
                                    - Rp{{strrev(implode('.',str_split(strrev(strval(-1*($sum_ekuitas+$sum_biaya))),3)))}}</strong>
                                  @else
                                    Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas+$sum_biaya)),3)))}}</strong>
                                  @endif
                                </strong>
                              </div>
                            </div>
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
    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();

        var url = "{{route('neraca')}}?year=" + year;
        if (month != null) {
            url = url + "&month=" + month;
            console.log('month');
        } else if(month == 0){
            url = url;
        }
        window.location.href = url;

    });
</script>
@endpush