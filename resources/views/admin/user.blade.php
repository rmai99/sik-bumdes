@extends('admin/layout/template')

@section('title', 'Data Pengguna')

@section('title-page', 'Data Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Pengguna</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-no-bordered table-hover" id="datatables">
                            <thead class="text-black font-weight-bold">
                                <th class="font-weight-bold">
                                    Nama
                                </th>
                                <th class="font-weight-bold">
                                    Email
                                </th>
                                <th class="font-weight-bold">
                                    Alamat
                                </th>
                                <th class="font-weight-bold">
                                    No.Telp
                                </th>
                                <th class="font-weight-bold">
                                    Status
                                </th>
                                <th class="disabled-sorting font-weight-bold">
                                    Aksi
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($companies as $item)
                                    <tr>
                                        <td>
                                            {{$item->name}}
                                        </td>
                                        <td>
                                            {{$item->user->email}}
                                        </td>
                                        <td>
                                            {{$item->address}}
                                        </td>
                                        <td>
                                            {{$item->phone_number}}
                                        </td>
                                        <td class="text-center" style="width:20%">
                                            {{-- <label class="switch">
                                                <input id="customSwitch1" class="custom-control-input" data-id="{{$item->id}}" type="checkbox" {{ $item->is_actived ? 'checked' : '' }}>
                                                <span data-id="{{$item->id}}" class="slider round" for="customSwitch1">
                                                <span class="slider round"></span>
                                              </label> --}}
                                            <div class="custom-control custom-switch">
                                                @if ($item->is_actived == 1)
                                                    <div class="offset-2 col-8 user pro">
                                                        Pro
                                                    </div>
                                                @else
                                                    <div class="offset-2 col-8 user reguler">
                                                        Reguler
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-right" style="width:8%">
                                            <button type="button" class="edit btn-icon" rel="tooltip" data-toggle="modal" data-target="#edit" value="{{$item->id}}">
                                                <i class="material-icons" style="color: #2B82BC;font-size:1.1rem;cursor: pointer;">edit</i>
                                            </button>
                                        </td>
                                    </tr>   
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" role="">
    <div class="modal-dialog modal-login" role="document">
        <div class="modal-content">
            <div class="card card-signup card-plain">
                <div class="modal-header">
                    <div class="card-header card-header-primary text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="material-icons">clear</i></button>
                        <h4 class="card-title">Perusahaan</h4>
                    </div>
                </div>
                <form method="POST" action="" id="form">
                    {{method_field('PUT')}}
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="text-dark font-weight-light">Nama Perusahaan</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-dark font-weight-bold" id="name">Group 1</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="text-dark font-weight-light">Email</h6>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-dark font-weight-bold" id="email"></h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="text-dark font-weight-light">Alamat</h6>
                                </div>
                                <div class="col-8">
                                        <h6 class="text-dark font-weight-bold" id="address">Jl. Sendowo</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="text-dark font-weight-light">No. Telp</h6>
                                </div>
                                <div class="col-8">
                                        <h6 class="text-dark font-weight-bold" id="phone_number">081263119340</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 align-self-center">
                                    <h6 class="text-dark font-weight-light">Status</h6>
                                </div>
                                <div class="col-8" id="is_actived">
                                    <select class="form-control" name="status">
                                        <option value="1">Pro</option>
                                        <option value="0">Reguler</option>
                                    </select>
                                </div>
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
{{-- @section('sweet')
<script>
    $(function () {
            $('.custom-control-input').change(function(){
            var status = $(this).prop('checked') == true ? 1 : 0;
            alert('Are you sure change user status?');
            var user_id = $(this).data('id');
            console.log(user_id);
            console.log($(this));
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{route('admin.user.index')}}/set_status/"+user_id,
                data: {'status': status},
                success: function(data){
                alert('successfully change user status');
                }
            });
            })
        });
</script>
@endsection --}}
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
        
        $(document).ready(function(){
            $(document).on('click', '.edit', function(){
                var id = $(this).attr('value');
                
                $.ajax({
                    type        : 'get',
                    url         : '{!!url('admin/user')!!}/'+id,
                    dataType    : 'html',
                    success     : function(data){
                        var servers = $.parseJSON(data);

                        $.each(servers, function(index, value){
                            var name = value.name;
                            var address = value.address;
                            var email = value.user.email;
                            var phone_number = value.phone_number;
                            var is_actived = value.is_actived;
                            
                            $('#name').text(name);
                            $('#address').text(address);
                            $('#email').text(email);
                            $('#phone_number').text(phone_number);
                            $('div#is_actived select').val(is_actived);

                        });
                    }

                });

                var action = "{{route('admin.user.index')}}/"+id;
                $('#form').attr('action',action);
            })
        })
    </script>
@endpush
@push('js')
    @include('sweetalert::alert')
@endpush