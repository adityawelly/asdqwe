@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Permissions</h4>
            {{ Breadcrumbs::render('permission') }}
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
                        <form id="formPermission" action="{{ route('permission.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Permission <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" value="crudtoggle" name="crudtoggle">
                                    <span class="form-check-sign">Buat crud permission</span>
                                </label>
                            </div>
                            <div class="form-group" id="crud-group" style="display:none">
                                <input type="checkbox" name="crud[]" value="create"> Create
                                <input type="checkbox" name="crud[]" value="read"> Read
                                <input type="checkbox" name="crud[]" value="update"> Update
                                <input type="checkbox" name="crud[]" value="delete"> Delete <br>
                                <input type="checkbox" name="crud[]" value="restore"> Restore
                                <input type="checkbox" name="crud[]" value="import"> Import
                                <input type="checkbox" name="crud[]" value="export"> Export
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
                            <table id="permission-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Permission</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->id }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td>{{ $permission->created_at }}</td>
                                            <td>
                                                <button type="button" 
                                                    data-toggle="tooltip" data-placement="top" title="Hapus"
                                                    class="btn btn-icon btn-round btn-danger" onclick="remove('{{ $permission->id }}', this)">
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
    var form = $('#formPermission');

    var validatedForm = form.validate();

    function submitForm(e) {
        if (form.valid()) {
            $(e).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    }

    var dt = $('#permission-table').dataTable({
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
                    url: '{{ route('permission.destroy', '') }}/'+id,
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

    $('input[name=crudtoggle]').on('change', function(e){
        $('#crud-group').toggle();
    });
</script>
@endsection