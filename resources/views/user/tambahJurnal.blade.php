@extends('user/layout/template')

@section('title', 'Jurnal Umum')

@section('title-page', 'Jurnal Umum')

@section('content')
<div class="card mt-0">
    <div class="header text-left m-3">
        <h3 class="title" style="font-weight: 400;">Tambah Jurnal</h3>
    </div>
    <form action="{{route('jurnal_umum.store')}}" method="POST">
    {{ csrf_field() }}
    <div id="test">
        <div class="card card-journal ml-4 mt-0 mb-4 puss">
            <div class="row d-flex justify-content-end">
                <div class="card-header">
                    <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">clear</i></button>
                </div>
            </div>
            <div class="row m-3 justify-content-between">
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">No kwitansi</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">receipt</i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-select" name="receipt[]" required="true" aria-required="true">
                    </div>
                </div>
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">Date</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">date_range</i>
                            </span>
                        </div>
                        <input type="date" class="form-control border-select" name="date[]" required="true" aria-required="true">
                    </div>
                </div>
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">Keterangan</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">description</i>
                            </span>
                        </div>
                        <textarea rows="1" style="width:80%" name="description[]" required="true" aria-required="true"></textarea>
                    </div>
                    @error('description[$key]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row m-3 d-flex">
                <div class="col-6 pl-0">
                    <h4 class="font-weight-bold">Debit</h4>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                            </div>
                            <select class="form-control border-select" name="id_debit_account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Jumlah</p>
                        <div class="input-group">
                            <input type="text" class="form-control border-select" name="debit[]" required="true" aria-required="true" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="" data-type="currency">
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0">
                    <h4 class="font-weight-bold">Kredit</h4>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                            </div>
                            <select class="form-control border-select" name="id_credit_account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <input type="text" class="form-control border-select" name="credit[]" required="true" aria-required="true" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?Rp" value="100" data-type="currency">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-3 justify-content-between">
        <div class="col-2">
            <button type="button" class="btn btn-primary m-1 pl-2 pr-2" style="float:right;" id="addRow">Tambah Jurnal</button>
        </div>
        <div class="col-2 justify-content-end">
            <button type="submit" class="btn btn-primary m-1 pl-2 pr-2" style="float:right;">SIMPAN</button>
        </div>
    </div>
</div>

@endsection


@push('js')

<script type="text/javascript">
    var test = `<div class="card card-journal ml-4 mt-0 mb-4 puss">
            <div class="row d-flex justify-content-end">
                <div class="card-header">
                    <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">clear</i></button>
                </div>
            </div>
            <div class="row m-3 justify-content-between">
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">No kwitansi</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">receipt</i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-select" name="receipt[]" required="true" aria-required="true">
                    </div>
                </div>
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">Date</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">date_range</i>
                            </span>
                        </div>
                        <input type="date" class="form-control border-select" name="date[]" required="true" aria-required="true">
                    </div>
                </div>
                <div class="col-4 pl-0">
                    <p class="font-weight-bold mb-0">Keterangan</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">description</i>
                            </span>
                        </div>
                        <textarea rows="1" style="width:80%" name="description[]" required="true" aria-required="true"></textarea>
                    </div>
                </div>
            </div>
            <div class="row m-3 d-flex">
                <div class="col-6 pl-0">
                    <h4 class="font-weight-bold">Debit</h4>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                            </div>
                            <select class="form-control border-select" name="id_debit_account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Jumlah</p>
                        <div class="input-group">
                            <input id="currency" type="text" class="form-control border-select" name="debit[]" required="true" aria-required="true" value="" data-type="currency">
                        </div>
                    </div>
                </div>
                <div class="col-6 pl-0">
                    <h4 class="font-weight-bold">Kredit</h4>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                            </div>
                            <select class="form-control border-select" name="id_credit_account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <input id="currency" type="text" class="form-control border-select" name="credit[]" required="true" aria-required="true" value="" data-type="currency">
                            {{--  <input type="text" placeholder="$1,000,000.00">  --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    $(function () {
        $('#addRow').click(function () {
            $('div#test').append(test);
        });
        $(document).on('click', '.remove', function () {
            $(this).parents('div.puss').remove();
        });
        $(document).on('keyup', '#currency', function () {
            // console.log('this', $(this))
            formatCurrency($(this));
        });

    });
    
</script>
<script>
    // Jquery Dependency
    $("input[data-type='currency']").on({
        keyup: function() {
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
@endpush