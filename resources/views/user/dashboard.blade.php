@extends('user/layout/template')

@section('title', 'Dashboard')

@section('title-page', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-warning card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">content_copy</i>
                    </div>
                    <p class="card-category">Saldo Kas</p>
                    <h3 class="card-title">1jt</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-success card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">store</i>
                    </div>
                    <p class="card-category">Modal</p>
                    <h3 class="card-title">8.3jt</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats mt-0">
                <div class="card-header card-header-danger card-header-icon pb-4 pt-4">
                    <div class="card-icon">
                        <i class="material-icons">info_outline</i>
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
        <div class="col-md-6">
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
                        <canvas id="laba-rugi" height="300px" width="525px" class="chartjs-render-monitor mt-3"
                            style="display: block"></canvas>
                    </div>
                    <h4 class="card-title">Laporan Laba Rugi</h4>
                    {{--  <p class="card-category">Last Campaign Performance</p>  --}}
                </div>
                {{--  <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> campaign sent 2 days ago
                    </div>
                </div>  --}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-chart">
                <div class="position-relative mb-4">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="laba-rugi" height="400" width="1050" class="chartjs-render-monitor"
                        style="display: block; height: 200px; width: 525px;"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Laporan Ekuitas</h4>
                    {{--  <p class="card-category">Last Campaign Performance</p>  --}}
                </div>
                {{--  <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> campaign sent 2 days ago
                    </div>
                </div>  --}}
            </div>
        </div>
    </div>
    <div class="row">
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
                        <canvas id="laba-rugi" height="400" width="1050" class="chartjs-render-monitor"
                            style="display: block; height: 200px; width: 525px;"></canvas>
                    </div>
                    <h4 class="card-title">Arus Akun Kas</h4>
                    {{--  <p class="card-category">Last Campaign Performance</p>  --}}
                </div>
                {{--  <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> campaign sent 2 days ago
                    </div>
                </div>  --}}
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-warning m-0">
                    <h4 class="card-title">Transaksi</h4>
                    {{--  <p class="card-category">New employees on 15th September, 2016</p>  --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p class="font-weight-bold mb-0">Membeli perlengkapan secara kredit</p>
                        </div>
                        <div class="col-4">
                            <p class="d-flex justify-content-end mb-0 font-20">Rp130.000
                        </div>
                        <div class="col">
                            <p class="font-14">23 Desember 2019</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="font-weight-bold mb-0">Membeli perlengkapan secara kredit</p>
                        </div>
                        <div class="col-4">
                            <p class="d-flex justify-content-end mb-0 font-20">Rp130.000
                        </div>
                        <div class="col">
                            <p class="font-14">23 Desember 2019</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="font-weight-bold mb-0">Membeli perlengkapan secara kredit</p>
                        </div>
                        <div class="col-4">
                            <p class="d-flex justify-content-end mb-0 font-20">Rp130.000
                        </div>
                        <div class="col">
                            <p class="font-14">23 Desember 2019</p>
                        </div>
                    </div>
                </div>
                {{--  <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead class="text-warning">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Country</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Dakota Rice</td>
                                <td>$36,738</td>
                                <td>Niger</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Minerva Hooper</td>
                                <td>$23,789</td>
                                <td>Curaçao</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sage Rodriguez</td>
                                <td>$56,142</td>
                                <td>Netherlands</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Philip Chaney</td>
                                <td>$38,735</td>
                                <td>Korea, South</td>
                            </tr>
                        </tbody>
                    </table>
                </div>  --}}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
</div>
@endsection