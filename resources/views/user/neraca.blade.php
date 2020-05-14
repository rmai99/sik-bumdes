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
                                                  @php
                                                      $sum += $assetArray[$i]['ending balance'][$y];
                                                  @endphp
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
                                          </b>{{$liabilityArray[$i]['sum']}}</b>
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
                                        @if ($equityArray[$i]['name'][$j] != "Laba Ditahan")
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
                                        @if ($equityArray[$i]['name'][$j] == "Laba Ditahan")
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