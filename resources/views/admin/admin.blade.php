@extends('admin/layout/template')

@section('title', 'Admin')

@section('title-page', 'Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">Admin</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{route('admin.manajemen_admin.create')}}"
                                class="btn btn-sm btn-primary addEmployee" id="hmm">Tambah Admin</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-no-bordered table-hover" id="datatables">
                            <thead class=" text-primary">
                                <th>
                                    Email
                                </th>
                                <th class="disabled-sorting text-right">
                                    Actions
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($admin as $a)
                                    <tr>
                                        <td>
                                            {{$a->email}}
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn-icon remove" id="{{$a->id}}">
                                                <i class="material-icons" style="color:#f44336;font-size:1.1rem;cursor: pointer;">close</i>
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

@endsection
@push('js')
@include('sweetalert::alert')
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
            var url = "{{ route('admin.manajemen_admin.index') }}/"+id;
            Swal.fire({
                title: 'Anda yakin ingin menghapus?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
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
                            'Dihapus!',
                            'Admin telah dihapus.',
                            'success'
                            )
                            $(this).closest('tr').remove();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Batal',
                    'Admin batal dihapus :)',
                    'error'
                    )
                }
            })
        });
        
    });
    </script>
@endpush