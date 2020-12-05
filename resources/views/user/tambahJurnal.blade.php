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
    <div class="card card-journal ml-4 mt-0 mb-4">
        <div class="row m-3 justify-content-between">
            <div class="col-lg-6 col-md-6 p-0">
                <div class="col-lg-12 col-md-12 pl-0">
                    <p class="font-weight-bold mb-0">No kwitansi</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">receipt</i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-select" name="receipt" required="true" aria-required="true" 
                            value="{{ old('receipt') }}">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 pl-0 mt-2">
                    <p class="font-weight-bold mb-0">Tanggal</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">date_range</i>
                            </span>
                        </div>
                        <input type="date" class="form-control border-select date" name="date" 
                            required="true" aria-required="true" value="{{ old('date') }}">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="col-lg-12 col-md-12 pl-0">
                    <p class="font-weight-bold mb-0">Keterangan</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">description</i>
                            </span>
                        </div>
                        <textarea class="description" rows="3" style="width:80%" name="description" required="true" aria-required="true">{{ old('description') }}</textarea>
                    </div>
                    @error('description[$key]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row m-3">
            <table class='table table-hover'>
                <thead class='table-header'>
                    <tr>
                        <th>
                            Akun
                        </th>
                        <th>
                            Debit
                        </th>
                        <th>
                            Kredit
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:40%">
                            <select class="form-control border-select credit" name="account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}" {{ $a->id == old('account.0') ? 'selected' : null }}>{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="width: 28%">
                            <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                            data-type="currency" value="{{old('amount_debit.0')}}" id="inputNominal">
                        </td>
                        <td style="width: 28%">
                            <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                            data-type="currency" value="{{ old('amount_credits.0') }}" id="inputNominal">
                        </td>
                        <td>
                            <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i></button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%">
                            <select class="form-control border-select credit" name="account[]" required>
                                <option selected="true" value="">Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}" {{ $a->id == old('account.1') ? 'selected' : null }}>{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="width: 28%">
                            <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                            data-type="currency" value="{{ old('amount_debit.1') }}" id="inputNominal">
                        </td>
                        <td style="width: 28%">
                            <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                            data-type="currency" value="{{ old('amount_credits.1') }}" id="inputNominal">
                        </td>
                        <td>
                            <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                                <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i></button>
                        </td>
                    </tr>
                    @foreach(old('account', []) as $parakey => $paravalue)
                        @if($parakey > 1)
                            <tr>
                                <td style="width:40%">
                                    <select class="form-control border-select credit" name="account[]" required>
                                        <option selected="true" value="">Pilih Akun</option>
                                        @foreach ($account as $a)
                                            <option value="{{ $a->id }}" {{ $a->id == $paravalue ? 'selected' : null }}>{{ $a->account_code }} - {{ $a->account_name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 28%">
                                    <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                                    data-type="currency" value="{{old('amount_debit')[$parakey]}}" id="inputNominal">
                                </td>
                                <td style="width: 28%">
                                    <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                                    data-type="currency" value="{{ old('amount_credits')[$parakey] }}" id="inputNominal">
                                </td>
                                <td>
                                    <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                                        <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i></button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="col-2">
                <button type="button" class="btn btn-primary m-1 pl-2 pr-2" style="float:left;" id="addRow">Tambah Akun</button>
            </div>
        </div>
    </div>
    <div class="row m-3 justify-content-center">
        <button id="save" class="btn btn-primary col-2 m-1 pl-2 pr-2" style="float:right;">SIMPAN</button>
    </div>
</div>

@endsection
@push('js')
<script>
    
    $(document).on('click', 'select', function(e) {
            $('select').chosen();
        });
    var test = `<tr>
                    <td style="width:40%">
                        <select class="form-control border-select credit" name="account[]" required>
                            <option selected="true" value="">Pilih Akun</option>
                            @foreach ($account as $a)
                                <option value="{{ $a->id }}" {{ $a->id == old('account.1') ? 'selected' : null }}>{{ $a->account_code }} - {{ $a->account_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width: 28%">
                        <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                        data-type="currency" value="{{old('amount_debit.1')}}" id="inputNominal">
                    </td>
                    <td style="width: 28%">
                        <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                        data-type="currency" value="{{ old('amount_credits.1') }}" id="inputNominal">
                    </td>
                    <td>
                        <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i></button>
                    </td>
                </tr>`;
    $(function () {
        $('#addRow').click(function () {
            $('tbody').append(test);
        });
        $(document).on('click', '.remove', function () {
            $(this).parents('tr').remove();
        });
    });

    $(document).on('keyup', '#currency', function () {
            // console.log('this', $(this))
            formatCurrency($(this));
        });

    $(document).on('input', 'input.date', function(e){
        var year = $("input.date").val();
        if(year){
            $('select.debit').prop('disabled', false);
            $('select.credit').prop('disabled', false);
        }
    })
    $(document).on('change', 'select.credit', function(e){
        var year = $("input.date").val();
        var debit = $("select.debit").val();
        var credit = $("select.credit").val();
        $.ajax({
            type        : 'get',
            url         : '{!!url('test')!!}',
            dataType    : 'html',
            data        : {'account':credit, 'date':year},
            success     : function(data){
                var servers = $.parseJSON(data);
                if (servers === undefined || servers.length == 0) {
                    $.ajax({
                        type        : 'get',
                        url         : '{!!url('detailAccount')!!}',
                        dataType    : 'html',
                        data        : {'id':debit},
                        success     : function(data){
                            var servers = $.parseJSON(data);
                            $.each(servers, function(index, value){
                                var position = value.position;
                                $('#awalCredit').val("0");
                                $('#posisiCredit').val(position);
                            });
                        }
                    });
                } else {
                    $.each(servers, function(index, value){
                        var posisiNormal = value.account.position;
                        var saldoAwal = value.amount;
                        console.log(posisiNormal, saldoAwal);

                        $('#awalCredit').val(saldoAwal);
                        $('#posisiCredit').val(posisiNormal);
                    });
                }
            }
        });

        if(year && credit && debit){
            $('input.amount').prop('disabled', false);
        }

    });

    $(document).on('change', 'select.debit', function(e){
        var year = $("input.date").val();
        var debit = $("select.debit").val();
        var credit = $("select.credit").val();
        $.ajax({
            type        : 'get',
            url         : '{!!url('test')!!}',
            dataType    : 'html',
            data        : {'account':debit, 'date':year},
            success     : function(data){
                var servers = $.parseJSON(data);
                if (servers === undefined || servers.length == 0) {
                    $.ajax({
                        type        : 'get',
                        url         : '{!!url('detailAccount')!!}',
                        dataType    : 'html',
                        data        : {'id':debit},
                        success     : function(data){
                            var servers = $.parseJSON(data);
                            $.each(servers, function(index, value){
                                var position = value.position;
                                $('#awalDebit').val("0");
                                $('#posisiDebit').val(position);
                            });
                        }
                    });
                } else {
                    $.each(servers, function(index, value){
                        var posisiNormal = value.account.position;
                        var saldoAwal = value.amount;
                        console.log(posisiNormal, saldoAwal);

                        $('#awalDebit').val(saldoAwal);
                        $('#posisiDebit').val(posisiNormal);
                    });
                }
            }
        });

        if(year && credit && debit){
            $('input.amount').prop('disabled', false);
        }

    });

    @if ($errors->has('id_debit_account'))
        var debit = "{{old('id_debit_account')}}";
        var credit = "{{old('id_credit_account')}}";
        $('.debit').val(debit);
        $('.credit').val(credit);
    @endif

    @if ($errors->has('balance'))
        swal.fire(
            'Gagal!',
            'Jumlah Debit dan Kredit tidak seimbang',
            'warning',
        )
    @endif
    @if (Session::has('error'))
        swal.fire(
            'Gagal!',
            'Saldo Akun Anda Tidak Mencukupi',
            'warning',
        )
    @endif

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
