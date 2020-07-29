@extends('user/layout/template')

@section('title', 'Neraca Awal')

@section('title-page', 'Neraca Awal')

@section('content')
@php
    $jumlah_debit = 0;
    $jumlah_kredit = 0;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header text-center mt-2">
                        <h3 class="title" style="font-weight: 400;">Neraca Awal</h3>
                        <p class=""><strong>Periode</strong> {{$year}} </p>
                    </div>
                    <div class="card-body">
                        <div class="toolbar">
                            <div class="d-flex justify-content-between">
                                <div class="col-md-2 pl-0">
                                    <div class="form-group">
                                        <strong class="mr-3">Tahun : </strong>
                                        <select class="pl-1 padding-select groupbyYear" id="select-year" style="border-radius: 3px;">
                                            <option value="0" disabled="true" selected="true">Tahun</option>
                                            @foreach ($years as $y)
                                            <option value="{{$y->year}}" {{ $year == $y->year ? 'selected' : '' }}>
                                                {{$y->year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3 pr-0 pl-0">
                                    <button type="button" class="btn btn-primary m-1 pl-2 pr-2" data-toggle="modal"
                                        data-target="#neracaAwalModal" style="float:right;">Tambah Neraca Awal</button>
                                </div>
                            </div>
                        </div>
                        <div class="material-datatables mt-4">
                            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                                width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Akun</th>
                                        <th>Debit</th>
                                        <th class="text-center">Kredit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($account_parent as $p)
                                    <tr data-toggle="collapse" data-target=".data{{ $p->id }}" class="accordion-toggle">
                                        <td style="width:60%">
                                            <strong> {{ $p->parent_code }} - {{ $p->parent_name }} </strong>
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @foreach ($p->classification as $c)
                                    <tr class="accordian-body collapse accordion-toggle data{{ $p->id }}">
                                        <td style="width:60%;padding-left: 1.5rem!important;">
                                            {{ $c->classification_code }} - {{ $c->classification_name }}
                                        </td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"></td>
                                        <td style="width:10%"></td>
                                    </tr>
                                    @foreach ($initial_balance as $item)
                                    @if ($item->account->id_classification == $c->id)
                                    <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun{{ $c->id }}">
                                        <td style="width:60%;padding-left: 3rem!important;">
                                            {{ $item->account->account_code }} - {{ $item->account->account_name }}
                                        </td>
                                        <td style="width:15%">
                                            @if ($item->account->position=="Debit")
                                                Rp{{strrev(implode('.',str_split(strrev(strval($item->amount)),3)))}}
                                            @php
                                            $jumlah_debit +=$item->amount;
                                            @endphp
                                            @endif
                                        </td>
                                        <td style="width:15%">
                                            @if ($item->account->position=="Kredit")
                                                Rp{{strrev(implode('.',str_split(strrev(strval($item->amount)),3)))}}
                                            @php
                                            $jumlah_kredit +=$item->amount;
                                            @endphp
                                            @endif
                                        </td>
                                        <td style="width:10%">
                                            <button type="button" rel="tooltip" title="Edit Akun" data-toggle="modal"
                                                data-target="#editNeracaAwalModal" class="editInitialBalance btn-icon"
                                                id="{{ $item->id }}">
                                                <i class="material-icons"
                                                    style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                            <button type="button" rel="tooltip" class="btn-icon remove" id="{{ $item->id }}">
                                                <i class="material-icons"style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </button>
                                        
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Jumlah</th>
                                        <th>
                                            @if ($jumlah_debit < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$jumlah_debit)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_debit)),3)))}}
                                            @endif
                                        </th>
                                        <th class="text-center">
                                            @if ($jumlah_kredit < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$jumlah_kredit)),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_kredit)),3)))}}
                                            @endif
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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

{{-- Tambah Neraca Awal Modal--}}
<div class="modal fade" id="neracaAwalModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Tambah Neraca Awal</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="{{route('neraca_awal.store')}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="card-body">

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                <input type="date" class="form-control" aria-describedby="date" placeholder="" name="date" aria-required="true" value="{{ old('date') }}">
                                @error('date')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <select class="w-100 pl-1 padding-select" name="id_account" style="border-radius: 3px;" id="account">
                                    <option value="0" selected disabled>Akun</option>
                                    @foreach ($account_parent as $data)
                                        @foreach ($data->classification as $item)
                                            @foreach ($item->account as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->account_code }} - {{ $akun->account_name }}</option>        
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                <input name="amount" type="text" class="form-control" required="true" aria-required="true" data-type="currency" value="{{ old('date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Neraca Awal --}}
<div class="modal fade" id="editNeracaAwalModal" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Edit Neraca Awal</h4>
                    </div>
                </div>
                <form class="form" method="POST" action="" id="editInitial">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <input type="hidden" class="form-control" name="initial_balance" id="id_initialBalances">
                            <div class="form-group date">
                                <h6 class="text-dark font-weight-bold m-0">Tanggal</h6>
                                <input type="date" class="form-control" name="edit_date" aria-describedby="date" value="{{ old('edit_date') }}" required>
                                @error('edit_date')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group id_account">
                                <h6 class="text-dark font-weight-bold m-0">Nama Akun</h6>
                                <select class="w-100 pl-1 padding-select" name="edit_acount" style="border-radius: 3px;" id="editAccount">
                                    <option value="0" selected disabled>Akun</option>
                                    @foreach ($account_parent as $data)
                                        @foreach ($data->classification as $item)
                                            @foreach ($item->account as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->account_code }} - {{ $akun->account_name }}</option>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group amount">
                                <h6 class="text-dark font-weight-bold m-0">Jumlah</h6>
                                <input type="text" class="form-control" aria-describedby="amountAccount" name="edit_amount" data-type="currency" value="{{ old('edit_amount') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btn-round">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        $(document).on('click', 'select', function(e) {
            $('select').chosen();
        });
        @if ($errors->has('date'))
            $('#neracaAwalModal').modal('show');
            var id_account = {{old('id_account')}};
            $('#account').val(id_account);
        @endif
        @if ($errors->has('edit_date'))
            $('#editNeracaAwalModal').modal('show');
            var id_account = {{old('edit_acount')}};
            $('#editAccount').val(id_account)
            var id = {{old('initial_balance')}};
            var action = "{{route('neraca_awal.index')}}/"+id;
            $('#editNeracaAwalModal').attr('action', action);
        @endif
        $('#datatables').DataTable({
            "info":     false,
            "ordering": false,
            "bPaginate": false,
            responsive          : true,
            language            : {
            search              : "_INPUT_",
            searchPlaceholder   : "Cari",
            }
        });
        
        var table = $('#datatables').DataTable();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Delete a record
        table.on('click', '.remove', function(e) {   
            e.preventDefault();
            var id = $(this).attr('id');
            
            // console.log(sid);
            var url = "{{ route('neraca_awal.index') }}/"+id;
            Swal.fire({
                title : 'Anda yakin menghapus neraca awal?',
                text : 'Anda tidak dapat mengembalikan data yang telah dihapus!',
                icon : 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: (response) => {
                            Swal.fire(
                                'Hapus!',
                                'Neraca awal telah dihapus.',
                                'success'
                            )
                            $(this).closest('tr').remove();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Batal',
                    'Data batal dihapus :)',
                    'error'
                    )
                }
            })
        });
    });

    $(document).on('change', '#select-year', function(e){
        e.preventDefault();
        var year = $("select.groupbyYear").val();

        var url = "{{route('neraca_awal.index')}}?year=" + year;
        window.location.href = url;
    });

    $(document).on('click', '.editInitialBalance', function () {
        @if ($errors->has('edit_date'))            
            $('span.invalid-feedback').remove();
        @endif
        var id = $(this).attr('id');
        $('#id_initialBalances').val(id);
        $.ajax({
            type        : 'GET',
            url         : '{!!url('detail_balance')!!}',
            data        : {'id':id},
            dataType    : 'html',
            success     : function(data){
                var servers = $.parseJSON(data);
                console.log(servers);
                $.each(servers, function(index, value){
                    var date = value.date;
                    var id_account = value.id_account;
                    var rupiah = '';
                    var convert = value.amount.toString().split('').reverse().join('');
                    for(var i = 0; i < convert.length; i++) if(i%3 == 0) rupiah += convert.substr(i,3)+',';
                    var amount = 'Rp'+ rupiah.split('',rupiah.length-1).reverse().join('');

                    $('div.date input').val(date);
                    $('div.id_account select').val(id_account);
                    $("div.amount input").val(amount);

                });
            }, error : function(){

            },
        });
        
        var action = "{{route('neraca_awal.index')}}/"+id;
        $('#editInitial').attr('action', action);

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
