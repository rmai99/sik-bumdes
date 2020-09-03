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
    setlocale(LC_ALL, 'id_ID');
    @endphp

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Jurnal Umum</h3>
                        <p class=""><strong>Periode</strong> {{$year}} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="row d-flex">
                                <div class="col-md-2 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun</strong>
                                        <select class="w-100 pl-1 padding-select groupbyYear" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Tahun</option>
                                            @foreach ($years as $y)
                                            <option id="year" name="year" value="{{$y->year}}"
                                                {{ $year == $y->year ? 'selected' : '' }}>{{$y->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 pl-md-0 pr-2">
                                    <div class="form-group">
                                        <strong class="mr-3">Bulan</strong>
                                        <select class="w-100 pl-1 padding-select groupbyMonth" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Bulan</option>
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
                                <div class="col-md-2 pl-md-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tanggal</strong>
                                        <select class="w-100 pl-1 padding-select groupbyDate" style="border-radius: 3px;" disabled>
                                            <option selected="true" value="null" selected>Semua</option>
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
                                <div class="col-md-2 mt-4">
                                    <button type="button" class="btn btn-primary" id="search">Cari</button>
                                </div>
                                <div class="col-md-4 mt-4 text-right">
                                    <a href="{{ route('jurnal_umum.create') }}" class="btn btn-primary">Tambah
                                        Jurnal</a>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables">
                            <table class="table" id="datatables" cellspacing="0" width="100%" class="table table-striped table-no-bordered table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2">Tanggal</th>
                                        <th rowspan="2" style="width:10%">No Kwitansi</th>
                                        <th rowspan="2">Keterangan</th>
                                        <th colspan="2">Nama Akun</th>
                                        <th colspan="2">Jumlah</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        @php
                                            $count = 0;
                                        @endphp
                                        @foreach ($item->journal()->orderBy('id', 'ASC')->get() as $jurnal)
                                            <tr>
                                                <td>{{ strftime("%d %B %G", strtotime($item->date)) }}</td>
                                                <td>{{ $item->receipt }}</td>
                                                <td>{{ $item->description }}</td>
                                                @if ($jurnal->position == "Debit")
                                                    <td>{{ $jurnal->account->account_code }} - {{ $jurnal->account->account_name }}</td>
                                                    <td></td>
                                                    <td>
                                                        Rp{{strrev(implode('.',str_split(strrev(strval($jurnal->amount)),3)))}}
                                                    </td>
                                                    <td></td>
                                                @else
                                                    <td></td>
                                                    <td>{{ $jurnal->account->account_code }} - {{ $jurnal->account->account_name }}</td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        Rp{{strrev(implode('.',str_split(strrev(strval($jurnal->amount)),3)))}}
                                                    </td>
                                                @endif
                                                @if ($count == 0)
                                                    <td style="width:10%" class="text-center">
                                                        <a href="{{ route('jurnal_umum.edit', $item->id) }}" class="btnEditJournal btn-icon" >
                                                            <i class="material-icons" style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                                        </button>
                                                        <button type="button" class="btn-icon remove" id="{{ $item->id }}">
                                                                <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                                        </button>
                                                    </td>
                                                @else
                                                <td></td>
                                                @endif
                                                @php
                                                    $count++;
                                                @endphp
                                            </tr>
                                            @endforeach
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

{{-- Modal Edit Jurnal --}}
<div class="modal fade" id="editJournal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <form class="form" method="POST" action="" id="formEditJournal">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <input type="hidden" id="id_detail" name="id_detail">
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
                        <div class="card-body detail">
                            {{-- <input type="hidden" name="id_detail"> --}}
                            <div class="form-group description">
                                <h6 class="text-dark font-weight-bold m-0">Keterangan</h6>
                                <input type="text" class="form-control" name="description" value="{{ old('description') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group receipt">
                                        <h6 class="text-dark font-weight-bold m-0">No Kwitansi</h6>
                                        <input type="text" class="form-control" name="receipt" value="{{ old('receipt') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group date">
                                        <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                        <input type="date" class="form-control date" name="date" value="{{ old('date') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group debit">
                                        <h6 class="text-dark font-weight-bold m-0">Debit Akun</h6>
                                        <input type="hidden" name="id_debit">
                                        <select class="form-control debit" name="id_debit_account" required>
                                            <option value="" selected="true">Select Akun</option>
                                            @foreach ($account as $a)
                                                <option value="{{$a->id}}">
                                                    {{$a->account_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_debit_account')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="hidden" id="awalDebit" name="awalDebit">
                                        <input type="hidden" id="posisiDebit" name="posisiDebit">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group credit">
                                        <h6 class="text-dark font-weight-bold m-0">Kredit Akun</h6>
                                        <input type="hidden" name="id_credit">
                                        <select class="form-control credit" name="id_credit_account" required>
                                            <option value="" selected="true">Select Akun</option>
                                            @foreach ($account as $a)
                                                <option value="{{$a->id}}">
                                                    {{$a->account_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_credit_account')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <input type="hidden" id="awalCredit" name="awalCredit">
                                        <input type="hidden" id="posisiCredit" name="posisiCredit">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12">
                                    <div class="form-group amount">
                                        <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                        <input type="text" class="form-control" name="amount" value="{{ old('amount') }}" data-type="currency" 
                                        id="inputNominal" onfocusout="namaFungsi()" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-round">Simpan</button>
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
            "ordering": false,
            language            : {
            search              : "_INPUT_",
            searchPlaceholder   : "Cari",
            }
        });
        var table = $('#datatables').DataTable();

        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        //Delete Record
        table.on('click', '.remove', function(e){
            e.preventDefault();
            var id = $(this).attr('id');

            var url = "{{route('jurnal_umum.index')}}/"+id;
            Swal.fire({
                title : 'Anda yakin menghapus jurnal?',
                text : 'Anda tidak dapat mengembalikan data yang telah dihapus!',
                icon : 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        type        : 'delete',
                        url         : url,
                        dataType    : 'JSON',
                        success     : (response) => {
                            Swal.fire(
                                {title: 'Dihapus!',
                                text: 'Jurnal telas dihapus',
                                icon: 'success',
                                timer: 300
                            })
                            $(this).closest('tr').remove();
                            location.reload();
                        }
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Batal',
                    'Data batal dihapus :)',
                    'error'
                    )
                }
            })
        })
    });

    $(document).on('click', '#search', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();
        var month = $("select.groupbyMonth").val();
        var day = $("select.groupbyDate").val();

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
        
        var month = $("select.groupbyMonth").val();
        
        if (month != null) {
            $('select.groupbyDate').prop('disabled', false);
        }
    });
    
    $(document).on('change', 'select.groupbyMonth', function(e){
        $('select.groupbyDate').prop('disabled', false);
    });

</script>
<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keydown: function() {
            formatCurrency($(this));
        },
        click : function(){
            formatCurrency($(this));
        }
    });

    function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.
    
    // get input value
    var input_val = input.val();
    
    // don't validate empty input
    if (input_val === "") { return; }
    
    // original length
    var original_len = input_val.length;

    // initial caret position 
    var caret_pos = input.prop("selectionStart");
        
    // check for decimal
    if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);
        
        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val = "Rp" + left_side + "." + right_side;

    } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        // console.log('input_val', input_val)
        input_val = formatNumber(input_val);
        input_val = "Rp" + input_val;
        
    }
    
    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>
@include('sweetalert::alert')
@endpush