@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data User Aplikasi</h4>
            {{ Breadcrumbs::render('user') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <b>Perhatian!</b> User baru ditambahkan saat menambahkan karyawan baru
                </div>
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            {{-- <div class="btn-group ml-auto">
                                <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('user.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                    <a class="dropdown-item" href="{{ route('user.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                    <a class="dropdown-item" href="{{ route('user.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Nama User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Extra Permission</th>
                                        <th style="width:13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->employee->registration_number }}</td>
                                            <td>{{ $user->employee->fullname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge badge-info">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($user->permissions as $permission)
                                                    <span class="badge badge-success">{{ $permission->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        onclick="location.assign('{{ route('user.edit', $user->id) }}')"
                                                        data-toggle="tooltip" data-placement="top" title="Edit user"
                                                        class="btn btn-sm btn-icon btn-round btn-primary">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Hapus"
                                                        class="btn btn-sm btn-icon btn-round btn-danger" onclick="remove('{{ $user->id }}', this)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
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
</div>
@endsection

@section('script')
<script>
    var dt = $('#user-table').dataTable({
        responsive: true,
    }).api();
    
    function remove(id, el) {
        swal({
            titleText: 'Apakah anda yakin?',
            text: "Data Karyawan dan User akan terhapus sekaligus",
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ route('user.destroy', '') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function(resp){
                        location.reload()
                    },
                    error: (error)=>{
                        this.close();
                        console.error(error);
                        showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
                    }
                })
            }
        });
    }
</script>
@endsection