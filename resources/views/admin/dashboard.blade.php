@extends('admin/layout/template')

@section('title', 'Dashboard')

@section('title-page', 'Dashboard')

@section('content')
@php
    $year = date('Y');
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-warning card-header-icon pb-4 pt-4">
                    <div class="card-icon" style="background: linear-gradient(60deg, #ffa726, #fb8c00);">
                        <i class="material-icons">account_circle</i>
                    </div>
                    <p class="card-category">Pengguna</p>
                    <h3 class="card-title">
                        {{$companies}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-success card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">account_circle</i>
                    </div>
                    <p class="card-category">Akun Reguler</p>
                    <h3 class="card-title">{{$reguler}}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-danger card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">account_circle</i>
                    </div>
                    <p class="card-category">Akun Pro</p>
                    <h3 class="card-title">{{$pro}}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-info card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                            <i class="material-icons">account_circle</i>
                    </div>
                    <p class="card-category">Admin</p>
                    <h3 class="card-title">{{$admin}}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title d-flex align-items-center">
                        Data Pengguna
                    </h3>
                    <div class="btn-group" data-toggle="btn-toggle">
                        @php
                            $year = date('Y');
                        @endphp
                        <select id="yearFilter" class="form-control" style="border: none; background-color:transparent;">
                            <option value="" selected disabled>-- YEAR --</option>
                            @foreach ($years as $y)
                                <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-icon btnFIlter" rel="tooltip">
                                <span class="material-icons" style="color: #2B82BC;font-size:1.1rem;cursor: pointer;">filter_list</span>
                        </button>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="laba-rugi" height="300px" class="chartjs-render-monitor mt-3"
                            style="display: block"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
</div>
@endsection
@push('js')
    <script>
        $(function() {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true
            
            $.ajax({
                type        : 'GET',
                url         : "{{ route('admin.user_register') }}",
                dataType    : 'JSON',
                success     : function(data){
                    window.salesChart = new Chart(document.getElementById('laba-rugi'), {
                        type: 'bar',
                        data: {
                            labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUL', 'AGT', 'SEP', 'OKT', 'NOV', 'DES'],
                            datasets: [{
                                backgroundColor: ['#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB', '#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB'],
                                borderColor: '#007bff',
                                data: data.in
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
                                    ticks: {
                                        beginAtZero:true,
                                    }
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
                }
            })
            
            $(document).on('click', '.btnFIlter', function(e){
                e.preventDefault();

                var year = $('#yearFilter').val();
                
                var path = "{{ route('admin.user_register') }}?year="+year;
                console.log(path);
                window.salesChart.destroy();
                $.ajax({
                    type        : 'GET',
                    url         : path,
                    dataType    : 'JSON',
                    success     : function(data){
                        window.salesChart = new Chart(document.getElementById('laba-rugi'), {
                            type: 'bar',
                            data: {
                                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUL', 'AGT', 'SEP', 'OKT', 'NOV', 'DES'],
                                datasets: [{
                                    backgroundColor: ['#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB', '#109CF1', '#FFB946', '#F7685B', '#2ED47A', '#885AF8', '#47C7EB'],
                                    borderColor: '#007bff',
                                    data: data.in
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
                                        ticks: {
                                            beginAtZero:true,
                                        }
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
                    }
                })


            })
        });
        
    </script>
@endpush