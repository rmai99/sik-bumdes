@extends('user/layout/template')

@section('title', 'Jurnal Umum')

@section('title-page', 'Jurnal Umum')

@section('content')
<div class="card mt-0">
    <div class="header text-left m-3">
        <h3 class="title" style="font-weight: 400;">Edit Jurnal</h3>
    </div>
    <form action="{{route('jurnal.update')}}" method="POST">
    {{ method_field('PUT') }}
    @csrf
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
                        <input type="hidden" value="{{$journal->id}}" name="id_detail">
                        <input type="text" class="form-control border-select" name="receipt" required="true" aria-required="true" 
                            value="{{$journal->id}}">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 pl-0">
                    <p class="font-weight-bold mb-0">Deskripsi</p>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">date_range</i>
                            </span>
                        </div>
                        <input type="date" class="form-control border-select date" name="date" 
                            required="true" aria-required="true" value="{{$journal->date}}">
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
                        <textarea class="description" rows="3" style="width:80%" name="description" required="true" aria-required="true">{{$journal->description}}</textarea>
                    </div>
                    @error('description')
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
                    @foreach ($journal->journal as $item)
                        <tr>
                            <td style="width:40%">
                                <select class="form-control border-select credit" name="account[]" required>
                                    <option selected="true" value="">Pilih Akun</option>
                                    @foreach ($account as $a)
                                        <option value="{{ $a->id }}" {{ $item->id_account === $a->id ? 'selected' : null }}>{{ $a->account_code }} - {{ $a->account_name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="width: 28%">
                                <input type="hidden" name="id_debit[]" value="{{$item->id}}">
                                <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                                data-type="currency" value="{{ $item->position === "Debit" ? 'Rp'.strrev(implode(',',str_split(strrev(strval($item->amount)),3))) : null }}" id="inputNominal">
                            </td>
                            <td style="width: 28%">
                                <input type="hidden" name="id_credit[]" value="{{$item->id}}">
                                <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                                data-type="currency" value="{{ $item->position === "Kredit" ? 'Rp'.strrev(implode(',',str_split(strrev(strval($item->amount)),3))) : null }}" id="inputNominal">
                            </td>
                            <td>
                                <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true" value="{{$item->id}}">
                                    <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @foreach(old('account', []) as $parakey => $paravalue)
                        @if($parakey > $journal->journal->count()-1)
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
    var test = `<tr>
                    <td style="width:40%">
                        <select class="form-control border-select credit" name="account[]" required>
                            <option selected="true" value="">Pilih Akun</option>
                            @foreach ($account as $a)
                                <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width: 28%">
                        <input type="hidden" name="id_debit[]">
                        <input type="text" id="currency" class="form-control border-select amount" name="amount_debit[]"
                        data-type="currency" value="{{ old('amount') }}" id="inputNominal">
                    </td>
                    <td style="width: 28%">
                        <input type="hidden" name="id_credit[]">
                        <input type="text" id="currency" class="form-control border-select amount" name="amount_credits[]"
                        data-type="currency" value="{{ old('amount') }}" id="inputNominal">
                    </td>
                    <td>
                        <button type="button" class="close remove" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons" style="color:#f44336;font-size:1.5rem;cursor: pointer;">close</i></button>
                    </td>
                </tr>`;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $(function () {
        $('#addRow').click(function () {
            $('tbody').append(test);
        });
        
        $(document).on('click', '.remove', function () {
            var id = $(this).attr('value');
            if(id == null){
                $(this).parents('tr').remove();
            } else{
                var url = "{{ route('jurnal_umum.index') }}/"+id+"/hapus";
                $.ajax({
                    type: "delete",
                    url: url,
                    dataType: "json",
                    success: (response) => {
                        $(this).parents('tr').remove();
                    }, error    : function(){
                        Swal.fire(
                            'Gagal!',
                            'Tidak Dapat Dihapus.',
                            'warning'
                        )
                    }
                });
            }
        });
    });

    @if (Session::has('error'))
        swal.fire(
            'Gagal!',
            'Saldo akun anda tidak cukup untuk melakukan transaksi ini',
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