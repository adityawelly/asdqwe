@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Role</h4>
            {{ Breadcrumbs::render('role') }}
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Tambah</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formRole" action="{{ route('role.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Role <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="pull-right">
                            <button class="btn btn-primary btn-md" type="button" onclick="submitForm(this)"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="role-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Role</th>
                                        <th>Guard</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->guard_name }}</td>
                                            <td>{{ $role->created_at }}</td>
                                            <td>
                                                <button type="button" 
                                                    onclick="location.assign('{{ route('role.permissions', $role->id) }}')"
                                                    data-toggle="tooltip" data-placement="top" title="Beri akses"
                                                    class="btn btn-icon btn-sm btn-round btn-success">
                                                    <i class="fas fa-lock-open"></i>
                                                </button>
                                                <button type="button" 
                                                    data-toggle="tooltip" data-placement="top" title="Hapus"
                                                    class="btn btn-icon btn-sm btn-round btn-danger" onclick="remove('{{ $role->id }}', this)">
                                                    <i class="fas fa-trash-alt"></i>
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
</div>
@endsection

@section('script')
<script>
    var modal = $('#pageModal');
    var form = $('#formRole');

    var validatedForm = form.validate();

    function submitForm(e) {
        if (form.valid()) {
            $(e).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    }

    var dt = $('#role-table').dataTable({
        responsive: true,
    }).api();
    
    function remove(id, el) {
        swal({
            titleText: 'Apakah anda yakin?',
            text: "Data tidak bisa dikembalikan",
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
                    url: '{{ route('role.destroy', '') }}/'+id,
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