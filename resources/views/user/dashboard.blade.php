@extends('user/layout/template')

@section('title', 'Dashboard')

@section('title-page', 'Dashboard')

@section('content')
@php
    $month = null;
    setlocale(LC_ALL, 'id_ID')
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-icon pb-4 pt-4">
                    <div class="card-icon" style="background: linear-gradient(60deg, #ffa726, #fb8c00);">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <p class="card-category">Saldo Kas</p>
                    <h3 class="card-title">
                        {{$sum}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-success card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">money</i>
                    </div>
                    <p class="card-category">Laba Rugi</p>
                    <h3 class="card-title">{{$saldo_berjalan}}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-danger card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">receipt</i>
                    </div>
                    <p class="card-category">Transaksi</p>
                    <h3 class="card-title">{{ $transaction }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-info card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">account_balance_wallet</i>
                    </div>
                    <p class="card-category">Akun</p>
                    <h3 class="card-title">{{ $account }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-warning m-0">
                    <h4 class="card-title">Transaksi</h4>
                     <p class="card-category">Transaksi Terbaru</p> 
                </div>
                <div class="card-body">
                    @foreach ($data as $item)
                        <div class="row">
                            <div class="col-8">
                                <p class="font-weight-bold mb-0">{{ $item->description}}</p>
                            </div>
                            <div class="col-4">
                                <p class="d-flex justify-content-end mb-0 font-20">
                                    Rp{{strrev(implode('.',str_split(strrev(strval($item->amount)),3)))}}
                                </p>
                            </div>
                            <div class="col">
                                <p class="font-14">{{ strftime("%d %B %G", strtotime($item->date)) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 card card-primary card-outline">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title d-flex align-items-center">
                    Arus Akun Kas
                </h3>
                <div class="btn-group" data-toggle="btn-toggle">
                    <button onclick="myFunction()" class="btn btn-default btn-sm" style="background: #66bb6a">Filter</button>
                </div>
            </div>
            <div class="card-body">
                <div id="myDIV" class="card-filter">
                    <h6>Periode</h6>
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control period" style="border: none; background-color:transparent;">
                                <option>Bulanan</option>
                                <option>Harian</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="" id="yearFilter" class="form-control" style="border: none; background-color:transparent;">
                                <option value="" selected disabled>-- YEAR --</option>
                                @foreach ($years as $y)
                                    <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 pt-2">
                            <select class="w-100 pl-1 padding-select groupbyMonth" style="border-radius: 3px" id="period-daily">
                                <option value="0" disabled="true" selected="true">Month</option>
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
                        <div class="col-12 text-center pt-2">
                            <button type="button" class="btn btn-primary btn-sm btnFIlter">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="myChart" class="chartjs-render-monitor mt-3"
                style="display: block"></canvas>
            </div>
            <!-- /.card-body-->
        </div>
    </div>
    {{--  <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                        <h3 class="card-title">Laporan Laba Rugi</h3>
                        <p class="card-category">Last Campaign Performance</p>
                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="laba-rugi" height="300px" class="chartjs-render-monitor mt-3"
                            style="display: block"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card card-chart">
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="stackedBarChart" style="height: 230px; min-height: 230px; display: block; width: 487px;" width="487" height="230" class="chartjs-render-monitor"></canvas>
                    </div>
                    <h4 class="card-title">Arus Akun Kas</h4>
                     <p class="card-category">Last Campaign Performance</p> 
                </div>
                 <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> campaign sent 2 days ago
                    </div>
                </div> 
            </div>
        </div>
    </div>  --}}
    @include('sweetalert::alert')
</div>
@endsection
@push('js')
    <script>
        $(function () {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $salesChart = $('#laba-rugi')
            
            var salesChart = new Chart($salesChart, {
                type: 'bar',
                data: {
                    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUL', 'AGT', 'SEP', 'OKT', 'NOV', 'DES'],
                    datasets: [{
                        backgroundColor: ['#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB', '#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB'],
                        borderColor: '#007bff',
                        data: [1000, 2000, 3000, 2500, 2700, 2500, 3000, 500, 200, 100, 400, 500, 500]
                    }, ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,

                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }
                                    return 'Rp' + value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })

            $.ajax({
                type: "GET",
                url: "{{ route('cash_flow') }}",
                dataType: "json",
                success: function(data){
                    // console.log(data.bulan);
                    window.myChart = new Chart(document.getElementById('myChart'), {
                    type: 'bar',
                    data: {
                        labels: data.bulan,
                        datasets: [{
                            label: 'cash in',
                            data: data.in,
                            backgroundColor: '#2ED47A',
                            borderColor: '#2ED47A',
                            borderWidth: 0
                        },
                        {
                            label: 'cash out',
                            data: data.out,
                            backgroundColor: '#47C7EB',
                            borderColor: '#47C7EB',
                            borderWidth: 0
                        }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000000) {
                                        value /= 1000000
                                        value += 'jt'
                                        return 'Rp' + value
                                    } else if(value <= -1000000){
                                        value /= 1000000*-1
                                        value += 'jt'
                                        return '-Rp' + value
                                    }
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            stacked: true,
                            ticks: {
                            beginAtZero: true
                            }
                        }]

                        }
                    }
                    });
                }
            })

            $(document).on('click', '.btnFIlter', function(e){
                e.preventDefault();
                var year = $('#yearFilter').val();
                var month = $('#period-daily').val();
                var period = $('.period').val();
                
                if (period == "Bulanan") {
                    window.myChart.destroy();
                    var path = "{{ route('cash_flow') }}?year="+year;
                    $.ajax({
                    type: "GET",
                    url: path,
                    dataType: "json",
                    success: function(data){
                        // console.log(data.bulan);
                        window.myChart = new Chart(document.getElementById('myChart'), {
                        type: 'bar',
                        data: {
                            labels: data.bulan,
                            datasets: [{
                                label: 'cash in',
                                data: data.in,
                                backgroundColor: '#2ED47A',
                                borderColor: '#2ED47A',
                                borderWidth: 0
                            },
                            {
                                label: 'cash out',
                                data: data.out,
                                backgroundColor: '#47C7EB',
                                borderColor: '#47C7EB',
                                borderWidth: 0
                            }
                            ]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                // display: false,
                                gridLines: {
                                    display: true,
                                    lineWidth: '4px',
                                    color: 'rgba(0, 0, 0, .2)',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: $.extend({
                                    // Include a dollar sign in the ticks
                                    callback: function (value, index, values) {
                                        if (value >= 1000000) {
                                            value /= 1000000
                                            value += 'jt'
                                            return 'Rp' + value
                                        } else if(value <= -1000000){
                                            value /= 1000000*-1
                                            value += 'jt'
                                            return '-Rp' + value
                                        }
                                    }
                                }, ticksStyle)
                            }],
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                beginAtZero: true
                                }
                            }]
    
                            }
                        }
                        });
                    }
                });  
                
                }
                else if(period == "Harian"){
                    window.myChart.destroy();
                    var path = "{{ route('cash_flow_daily') }}/"+year+"-"+month;
                    console.log(path);
                    $.ajax({
                    type: "GET",
                    url: path,
                    dataType: "json",
                    success: function(data){
                        // console.log(data.bulan);
                        window.myChart = new Chart(document.getElementById('myChart'), {
                        type: 'line',
                        data: {
                            labels: data.day,
                            datasets: [{
                                fill : false,
                                label: 'cash in',
                                data: data.in,
                                borderWidth         : 2,
                                lineTension         : 0,
                                spanGaps : true,
                                borderColor         : '#2ED47A',
                                pointRadius         : 3,
                                pointHoverRadius    : 7,
                                pointColor          : '#2ED47A',
                                pointBackgroundColor: '#2ED47A',
                            },
                            {
                                fill : false,
                                label: 'cash out',
                                data: data.out,
                                borderWidth         : 2,
                                lineTension         : 0,
                                spanGaps : true,
                                borderColor         : '#47C7EB',
                                pointRadius         : 3,
                                pointHoverRadius    : 7,
                                pointColor          : '#47C7EB',
                                pointBackgroundColor: '#47C7EB',
                            }
                            ]
                        },
                        options: {
                            responsive              : true,
                            scales: {
                                yAxes: [{
                                // display: false,
                                gridLines: {
                                    display: true,
                                    lineWidth: '4px',
                                    color: 'rgba(0, 0, 0, .2)',
                                    zeroLineColor: 'transparent'
                                },
                                ticks: $.extend({
                                    // Include a dollar sign in the ticks
                                    callback: function (value, index, values) {
                                        if (value >= 1000000) {
                                            value /= 1000000
                                            value += 'jt'
                                            return 'Rp' + value
                                        } else if(value <= -1000000){
                                            value /= 1000000*-1
                                            value += 'jt'
                                            return '-Rp' + value
                                        }
                                    }
                                }, ticksStyle)
                            }],
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                beginAtZero: true
                                }
                            }]
    
                            }
                        }
                        });
                    }
                });
                }
                
            });

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')

            var stackedBarChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            scales: {
                xAxes: [{
                stacked: true,
                }],
                yAxes: [{
                stacked: true
                }]
            }
            }

            var stackedBarChart = new Chart(stackedBarChartCanvas, {
            type: 'bar',
            options: stackedBarChartOptions
            })
        })
        document.getElementById("myDIV").style.display = "none";
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        document.getElementById("period-daily").style.display = "none";            
        $(document).on('change', 'select.period', function(e){
            var x = document.getElementById("period-daily");
            var period = $("select.period").val();
            if(period == "Harian"){
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        });
        

    </script>
@endpush