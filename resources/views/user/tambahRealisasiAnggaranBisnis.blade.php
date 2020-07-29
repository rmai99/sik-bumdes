@extends('user/layout/template')

@section('title', 'Laporan Rencana Anggaran')

@section('title-page', 'Laporan Rencana Anggaran')

@section('content')
@php
    if (isset($_GET['year'])) {
        $dt = $_GET['year'];
        $month = $_GET['month'];
    } else {
        $dt = date('Y');
        $month = date('m');
    }
    $count = 0;
    setlocale(LC_ALL, 'id_ID');
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Realisasi Anggaran</h3>
                        <p class=""><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{$dt}} </p>
                    </div>
                    <div class="card-body">
                        @error('null')
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data yang disimpan
                        </div>
                        @enderror
                        <div class="material-datatables mt-4">
                            <form class="form" method="POST" action="{{route('realisasi.store')}}">
                                {{ csrf_field() }}
                                <table id="datatables" class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0"
                                    width="100%" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nama Akun</th>
                                            <th>Anggaran</th>
                                            <th>Realisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($plan as $item)
                                            @if($item->budget_plan !=null)
                                                @if ($item->budget_plan->realization == null)
                                                    <tr>
                                                        <input type="hidden" value="{{$item->budget_plan->id}}" name="id[]">
                                                        <td style="width:40%;">
                                                            {{$item->name}}
                                                        </td>
                                                        <td style="width:15%">
                                                            Rp{{strrev(implode('.',str_split(strrev(strval($item->budget_plan->amount)),3)))}}
                                                        </td>
                                                        <td>
                                                            <input type="text" id="currency" class="form-control border-select amount" name="realisasi[]"
                                                            data-type="currency" value="">
                                                        </td>
                                                    </tr>
                                                    {{$count++}}
                                                @endif
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($count != 0)
                                <div class="justify-content-center float-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                @else
                                <div class="alert alert-warning text-center" role="alert">
                                    TIDAK ADA DATA YANG BISA DITAMBAH
                                  </div>
                                @endif
                            </form>
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
    $(document).on('click', 'select', function(e) {
        $('select').chosen();
    });
</script>
<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
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
