@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Departemen</h4>
            {{ Breadcrumbs::render('departemen') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-department')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Departemen
                                </button>
                            @endcan
                            @can('export-department')
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('department.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="{{ route('department.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="{{ route('department.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="department-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Divisi</th>
                                        <th>Kode Departemen</th>
                                        <th>Nama Departemen</th>
                                        <th>Deskripsi Departemen</th>
                                        @can('delete-department')
                                            <th>Status</th>
                                        @endcan
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $department->id }}</td>
                                            <td>{!! $department->division->division_name ?? '<span class="badge badge-warning">Belum dipilih</span>' !!}</td>
                                            <td>{{ $department->department_code }}</td>
                                            <td>{{ $department->department_name }}</td>
                                            <td>{{ $department->department_description }}</td>
                                            @can('delete-department')
                                                <td>{!! $department->trashed() ? '<span class="badge badge-danger">Terhapus</span>':'<span class="badge badge-primary">Tersedia</span>' !!}</td>
                                            @endcan
                                            <td>
                                                <div class="btn-group">
                                                @if ($department->trashed())
                                                    @can('restore-department')
                                                        <button type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Kembalikan" class="btn btn-icon btn-xs btn-success" onclick="restore('{{ $department->id }}', this)">
                                                            <i class="fas fa-recycle"></i>
                                                        </button>
                                                    @endcan
                                                @else
                                                    @can('update-department')
                                                        <button type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Assign Head" class="btn btn-icon btn-xs btn-success" onclick="assign(this, '{{ $department->id }}')">
                                                            <i class="fas fa-user-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-xs btn-primary" onclick="edit('{{ $department->id }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-department')
                                                        <button type="button"
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-xs btn-danger" onclick="remove('{{ $department->id }}', this, false)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                                @can('restore-department')
                                                    <button type="button"
                                                        data-toggle="tooltip" data-placement="top" title="Hapus Permanen" class="btn btn-icon btn-xs btn-danger" onclick="remove('{{ $department->id }}', this, true)">
                                                        <i class="fas fa-window-close"></i>
                                                    </button>
                                                @endcan
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
    var modal = $('#pageModal');
    var form = $('#formModal');

    var validatedForm = form.validate({
        rules: {
            department_code: "required",
            department_name: "required",
            division_id: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#department-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('department') }}');
        modal.find('.modal-title').text('Tambah Departemen');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('department') }}/'+id);
        $.ajax({
            url: '{{ url('department') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('select[name=division_id]').val(resp.division_id).trigger('change');
                form.find('input[name=department_code]').val(resp.department_code);
                form.find('input[name=department_name]').val(resp.department_name);
                form.find('input[name=department_description]').val(resp.department_description);
                modal.find('.modal-title').text('Edit Departemen');
                form.append('@method('PUT')');
                modal.modal('toggle');
            },
            error: function(error){
                console.error(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            },
            complete: function(){
                $(el).removeClass('is-loading').attr('disabled', false);
            }
        })
    }

    function remove(id, el, flag) {
        swal({
            titleText: 'Apakah anda yakin?',
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
                    url: '{{ url('department') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _method: 'DELETE',
                        force: flag
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

    function restore(id, el) {
        swal({
            titleText: 'Apakah anda yakin?',
            text: "Data akan dikembalikan",
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, kembalikan!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ url('department/restore') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
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

    function assign(el, id) {
        var select_managers = $('select[name=manager_id]');
        var select_supervisors = $('select[name=supervisor_id]');
        var modal = $('#assignModal');

        $.ajax({
            url: '{{ route('department.heads') }}',
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            data: {
                department_id: id
            },
            success: function(resp){
                select_managers.select2({
                    theme: 'bootstrap',
                    data: resp.managers,
                    placeholder: 'Pilih Opsi'
                });
                select_supervisors.select2({
                    theme: 'bootstrap',
                    data: resp.supervisors,
                    placeholder: 'Pilih Opsi'
                });

                if (resp.department.manager) {
                    select_managers.val(resp.department.manager.id).trigger('change');
                }

                if (resp.department.supervisor) {
                    select_supervisors.val(resp.department.supervisor.id).trigger('change');
                }

                $(el).removeClass('is-loading').attr('disabled', false);
                $('#assignFormModal').attr('action', '{{ route('department.assign', '') }}/'+id)
                modal.modal('toggle');
            },
            error: function(err){
                console.error(err);
            }
        });
    }

    modal.on("hidden.bs.modal", function (e) {
        form.trigger('reset');
        validatedForm.resetForm();
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="pageModal" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="formModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Pilih Divisi <span class="required-label">*</span></label>
                            <select name="division_id" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->division_code.'-'.$division->division_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Kode Departemen <span class="required-label">*</span></label>
                            <input type="text" name="department_code" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Departemen <span class="required-label">*</span></label>
                            <input type="text" name="department_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Deskripsi Departemen</label>
                            <input type="text" name="department_description" class="form-control">
                        </div>
						<div class="form-group">
                            <label for="">Gambar Untuk Job Portal</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="assignModal" role="dialog" aria-labelledby="assignModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Assign Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="assignFormModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Pilih Manager Kepala <span class="required-label">*</span></label>
                            <select name="manager_id" class="form-control selectpicker" style="width:100%" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Supervisor Kepala <span class="required-label">*</span></label>
                            <select name="supervisor_id" class="form-control selectpicker" style="width:100%" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
