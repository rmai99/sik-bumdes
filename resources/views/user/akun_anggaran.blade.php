@extends('user/layout/template')

@section('title', 'Rencana Anggaran Bisnis')

@section('title-page', 'Rencana Anggaran Bisnis')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row pt-3 pb-3 mr-1 d-flex justify-content-end">
                            <div class="col-2 p-0">
                                <button type="button" class="btn btn-primary m-1 pl-2 pr-2" data-toggle="modal"
                                        data-target="#neracaAwalModal" style="float:right;">Tambah Akun</button>
                            </div>
                        </div>
                        <div class="card-header card-header-warning m-1 p-2 d-flex justify-content-between"
                            data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false"
                            aria-controls="collapse1">
                            <h4 class="card-title mb-0">Penerimaan</h4>
                            <i class="material-icons">keyboard_arrow_down</i>
                        </div>

                        <div class="card-body collapse pt-0 pb-0 mb-0" id="collapse1">
                            <table class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0"
                                width="100%">
                                @foreach ($account as $item)
                                <tr>
                                    <th class="p-2">
                                        <strong>{{$item->name}}</strong>
                                    </th>
                                    <th></th>
                                </tr>
                                @foreach ($item->budget_account as $b)
                                <tr>
                                    <td style="width:10%" class="p-2">
                                        {{$b->name}}
                                    </td>
                                    <td style="width:15%" class="text-right">
                                        <button class="btnEditAccount btn-icon" type="button" rel="tooltip"
                                            title="Edit Akun" data-toggle="modal" data-target="#editAkunModal"
                                            value="">
                                            <i class="material-icons"
                                                style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                        </button>
                                        <button type="button" class="btn-icon remove" id="">
                                            <i class="material-icons"
                                                style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </table>
                        </div>
                        <div class="card-header card-header-warning m-1 p-2 d-flex justify-content-between"
                            data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false"
                            aria-controls="collapse2">
                            <h4 class="card-title mb-0">Belanja</h4>
                            <i class="material-icons">keyboard_arrow_down</i>
                        </div>

                        <div class="card-body collapse pt-0 pb-0 mb-0" id="collapse2">
                            <table class="table table-striped table-no-bordered table-hover mb-0" cellspacing="0"
                                width="100%">
                                @foreach ($type as $item)
                                    <tr>
                                        <td style="width:10%" class="p-2">
                                            {{$item->name}}
                                        </td>
                                        <td style="width:15%" class="text-right">
                                            <button class="btnEditAccount btn-icon" type="button" rel="tooltip"
                                                title="Edit Akun" data-toggle="modal" data-target="#editAkunModal"
                                                value="">
                                                <i class="material-icons"
                                                    style="color: #9c27b0;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                            <button type="button" class="btn-icon remove" id="">
                                                <i class="material-icons"
                                                    style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
@endsection