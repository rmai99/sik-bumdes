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
                        <input type="text" class="form-control border-select" name="receipt[]">
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
                        <input type="date" class="form-control border-select" name="date[]">
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
                        <textarea rows="1" style="width:80%" name="description[]"></textarea>
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
                            <select class="form-control border-select" name="id_debit_account[]">
                                <option selected disabled>Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Jumlah</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="number" class="form-control border-select" name="debit[]">
                            {{-- <input name="amount" type="number" class="form-control border-select" id="amount_of_debit" onkeyup="copytextbox();" name="debit[]">
                            <input type="text" class="form-control" id="result1" disabled> --}}
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
                            <select class="form-control border-select" name="id_credit_account[]">
                                <option selected disabled>Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="number" class="form-control border-select" name="credit[]">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-3 justify-content-between">
        <div class="col-2">
            <button type="button" class="btn btn-primary m-1 pl-2 pr-2" style="float:right;" id="addRow">Tambah Neraca Awal</button>
        </div>
        <div class="col-2 justify-content-end">
            <button type="submit" class="btn btn-primary m-1 pl-2 pr-2" style="float:right;">Tambah Neraca Awal</button>
        </div>
    </div>
</form>
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
                        <input type="text" class="form-control border-select" name="receipt[]">
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
                        <input type="date" class="form-control border-select" name="date[]">
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
                        <textarea rows="1" style="width:80%" name="description[]"></textarea>
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
                            <select class="form-control border-select" name="id_debit_account[]">
                                <option selected disabled>Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Jumlah</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="number" class="form-control border-select" name="debit[]">
                            {{-- <input name="amount" type="number" class="form-control border-select" id="amount_of_debit" onkeyup="copytextbox();" name="debit[]">
                            <input type="text" class="form-control" id="result1" disabled> --}}
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
                            <select class="form-control border-select" name="id_credit_account[]">
                                <option selected disabled>Pilih Akun</option>
                                @foreach ($account as $a)
                                    <option value="{{ $a->id }}">{{ $a->account_code }} - {{ $a->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 pl-0">
                        <p class="mb-0">Nama Akun</p>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="number" class="form-control border-select" name="credit[]">
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
            $(this).parents('div').remove();
        });

    });
    
</script>
<script>
    function copytextbox() {
        var angka = document.getElementById('amount_of_debit').value;
        var rupiah = '';		
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            // 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('')
        document.getElementById('result1').value = "Rp"+rupiah.split('',rupiah.length-1).reverse().join('');
    }
</script>
@endpush