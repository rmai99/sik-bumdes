@extends('user/layout/template')

@section('title', 'Jurnal Umum')

@section('title-page', 'Jurnal Umum')

@section('content')
    @php
    if(isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day'])){
        if (isset($_GET['year'], $_GET['month'], $_GET['day'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = $_GET['day'];
            // # code...
        }elseif(isset($_GET['year'], $_GET['month'])){
            
            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = null;
        }elseif(isset($_GET['year'])){

            $year = $_GET['year'];
            $month = null;
            $day = null;
        }
    } else {
        $year = date('Y');
        $month = null;
        $day = null;
    }
    @endphp

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Jurnal Umum</h3>
                        <p class=""><strong>Periode</strong> 2019 </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-1 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun</strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear" id="" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Year</option>
                                            @foreach ($years as $y)
                                            <option id="year" name="year" value="{{$y->year}}"
                                                {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 pl-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Bulan</strong>
                                        <select class="w-100 pl-1 padding-select groupbyMonth" id="" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Month</option>
                                            <option value="0">All</option>
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
                                <div class="col-md-2 pl-0 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tanggal</strong>
                                        <select class="w-100 pl-1 padding-select groupbyDate" id="" style="border-radius: 3px;">
                                            <option selected="true" value="null" selected>All</option>
                                            <option value="01" {{ $day == '01' ? 'selected' : '' }}>1</option>
                                            <option value="02" {{ $day == '02' ? 'selected' : '' }}>2</option>
                                            <option value="03" {{ $day == '03' ? 'selected' : '' }}>3</option>
                                            <option value="04" {{ $day == '04' ? 'selected' : '' }}>4</option>
                                            <option value="05" {{ $day == '05' ? 'selected' : '' }}>5</option>
                                            <option value="06" {{ $day == '06' ? 'selected' : '' }}>6</option>
                                            <option value="07" {{ $day == '07' ? 'selected' : '' }}>7</option>
                                            <option value="08" {{ $day == '08' ? 'selected' : '' }}>8</option>
                                            <option value="09" {{ $day == '09' ? 'selected' : '' }}>9</option>
                                            <option value="10" {{ $day == '10' ? 'selected' : '' }}>10</option>
                                            <option value="11" {{ $day == '11' ? 'selected' : '' }}>11</option>
                                            <option value="12" {{ $day == '12' ? 'selected' : '' }}>12</option>
                                            <option value="13" {{ $day == '13' ? 'selected' : '' }}>13</option>
                                            <option value="14" {{ $day == '14' ? 'selected' : '' }}>14</option>
                                            <option value="15" {{ $day == '15' ? 'selected' : '' }}>15</option>
                                            <option value="16" {{ $day == '16' ? 'selected' : '' }}>16</option>
                                            <option value="17" {{ $day == '17' ? 'selected' : '' }}>17</option>
                                            <option value="18" {{ $day == '18' ? 'selected' : '' }}>18</option>
                                            <option value="19" {{ $day == '19' ? 'selected' : '' }}>19</option>
                                            <option value="20" {{ $day == '20' ? 'selected' : '' }}>20</option>
                                            <option value="21" {{ $day == '21' ? 'selected' : '' }}>21</option>
                                            <option value="22" {{ $day == '22' ? 'selected' : '' }}>22</option>
                                            <option value="23" {{ $day == '23' ? 'selected' : '' }}>23</option>
                                            <option value="24" {{ $day == '24' ? 'selected' : '' }}>24</option>
                                            <option value="25" {{ $day == '25' ? 'selected' : '' }}>25</option>
                                            <option value="26" {{ $day == '26' ? 'selected' : '' }}>26</option>
                                            <option value="27" {{ $day == '27' ? 'selected' : '' }}>27</option>
                                            <option value="28" {{ $day == '28' ? 'selected' : '' }}>28</option>
                                            <option value="29" {{ $day == '29' ? 'selected' : '' }}>29</option>
                                            <option value="30" {{ $day == '30' ? 'selected' : '' }}>30</option>
                                            <option value="31" {{ $day == '31' ? 'selected' : '' }}>31</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <button type="button" class="btn btn-primary" id="search">Cari</button>
                                </div>
                                <div class="col-md-5 mt-4 text-right">
                                    <a href="{{ route('jurnal_umum.create') }}" class="btn btn-primary">Tambah
                                        Jurnal</a>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover"
                                cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No Kwitansi</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Akun</th>
                                        <th>Position</th>
                                        <th>Jumlah</th>
                                        <th class="text-center" style="width:10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No Kwitansi</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Akun</th>
                                        <th>Position</th>
                                        <th>Jumlah</th>
                                        <th class="text-center" style="width:10%">Aksi</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($data as $journal)
                                    <tr>
                                        <td>{{ $journal->date }}</td>
                                        <td></td>
                                        <td>{{ $journal->description }}</td>
                                        <td>{{ $journal->account->account_name }}</td>
                                        <td>{{ $journal->position }}</td>
                                        <td>
                                            Rp{{strrev(implode('.',str_split(strrev(strval($journal->amount)),3)))}}
                                        </td>
                                        <td class="text-center">
                                            <a type="button" rel="tooltip" title="Edit Akun" id="{{ $journal->id }}"
                                                data-toggle="modal" data-target="#editJurnal">
                                                <i class="material-icons"
                                                    style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </a>
                                            <a type="button" rel="tooltip" title="Hapus" id="{{ $journal->id }}">
                                                <i class="material-icons"
                                                    style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
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

{{-- Modal Tambah Jurnal --}}
<div class="modal fade" id="editJurnal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <form class="form" method="" action="">
            <div class="modal-content">
                <div class="card card-signup card-plain">
                    <div class="modal-header">
                        <div class="card-header card-header-primary text-center">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons">clear</i></button>
                            <h4 class="card-title">Edit Jurnal</h4>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Keterangan</h6>
                                <input type="text" class="form-control" id="" name="" aria-describedby="ketJurnal"
                                    value="Membeli peralatan secara kredit">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">No Kwitansi</h6>
                                        <input type="text" class="form-control" id="" name=""
                                            aria-describedby="kwitansiJurnal" value="123A">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                        <input type="date" class="form-control" id="" name=""
                                            aria-describedby="kwitansiJurnal" value="123A">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">Debit Akun</h6>
                                        <select class="form-control" id="">
                                            <option disabled="true" selected="true">Parent Akun</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                        <input type="number" class="form-control" id="" name=""
                                            aria-describedby="amountDebit" value="123A">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">Debit Akun</h6>
                                        <select class="form-control" id="">
                                            <option disabled="true" selected="true">Parent Akun</option>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                        <input type="number" class="form-control" id="" name=""
                                            aria-describedby="amountDebit" value="123A">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="#pablo" class="btn btn-primary btn-round">Simpan</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@push('js')

<script>
    $(document).ready(function () {
        $('#datatables').DataTable({
            "pagingType"        : "full_numbers",
            "lengthMenu"        : [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                                ],
            responsive          : true, 
            language            : {
            search              : "_INPUT_",
            searchPlaceholder   : "Cari",
            }
        });
    });

    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();
        var day = $("select.groupbyDate").val();
        // console.log(day);
        var url = "{{route('jurnal_umum.index')}}?year=" + year;
        if (month != null) {
            url = url + "&month=" + month;
            console.log('month');
        }
        if (month != 'null' && day != 'null') {
            url = url + "&day=" + day;
            console.log('day');
        }
        console.log(url);
        window.location.href = url;
    });
    $(document).ready(function() {
        
        var month = $("select.month").val();
        
        if (month != null) {
            $('select.day').prop('disabled', false);
        }
    });
    $(document).on('change', 'select.month', function(e){
        $('select.day').prop('disabled', false);
    });
    
</script>
@endpush