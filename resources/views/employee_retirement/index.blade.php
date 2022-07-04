@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Karyawan Resign</h4>
            {{ Breadcrumbs::render('resign') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('read-resign')
                                <a class="btn btn-success btn-round ml-auto" href="{{ route('employee.retirement_excel') }}">
                                    <i class="far fa-file-excel"></i>
                                    Export Excel
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employee-retirement-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Resign</th>
                                        <th>Alasan</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee_retirements as $employee_retirement)
                                        <tr>
                                            <td>{{ $employee_retirement->id }}</td>
                                            <td>{{ $employee_retirement->employee->registration_number }}</td>
                                            <td>{{ $employee_retirement->employee->fullname }}</td>
                                            <td>{{ date('d-m-Y', strtotime($employee_retirement->date_of_retirement)) }}</td>
                                            <td>{{ $employee_retirement->reason }}</td>
                                            <td>{{ $employee_retirement->note }}</td>
                                            <td>
                                                <button type="button" 
                                                    data-toggle="tooltip" data-placement="top" title="Lihat" class="btn btn-icon btn-round btn-sm btn-default" onclick="location.assign('{{ route('employee.show', $employee_retirement->employee->id) }}')">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                @can('update-resign')
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit('{{ $employee_retirement->id }}', this)">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                @endcan
                                                @can('delete-resign')
                                                    <button type="button" 
                                                        data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove('{{ $employee_retirement->id }}', this, false)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
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
            employee_retirement_code: "required",
            employee_retirement_name: "required"
        }
    });

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#employee-retirement-table').dataTable({
        responsive: true,
    }).api();

    function edit(id, el) {
        form.attr('action', '{{ route('employee.retirement_update', '') }}/'+id);
        $.ajax({
            url: '{{ route('employee.retirement_show', '') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=date_of_retirement]').val(resp.date_of_retirement);
                form.find('select[name=reason]').val(resp.reason);
                form.find('textarea[name=note]').val(resp.note);
                modal.find('.modal-title').text('Edit Data Resign');
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
                    url: '{{ route('employee.retirement_destroy', '') }}/'+id,
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

    modal.on("hidden.bs.modal", function (e) {
        form.trigger('reset');
        validatedForm.resetForm();
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
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
                        @method('PUT')
                        <div class="form-group">
                            <label for="">Tanggal Resign <span class="required-label">*</span></label>
                            <input name="date_of_retirement" type="text" class="form-control datepicker" required>
                        </div>
                        <div class="form-group">
                            <label for="">Alasan <span class="required-label">*</span></label>
                            <select name="reason" class="form-control" required>
                                <option value="Habis Kontrak">Habis Kontrak</option>
                                <option value="PHK - Pensiun Dini">PHK - Pensiun Dini</option>
                                <option value="PHK - Sakit">PHK - Sakit</option>
                                <option value="PHK - Kasus Pidana">PHK - Kasus Pidana</option>
                                <option value="PHK - Berkelahi">PHK - Berkelahi</option>
                                <option value="Resign Hamil">Resign Hamil</option>
                                <option value="Resign Kemauan Sendiri">Resign Kemauan Sendiri</option>
                                <option value="Resign Sakit">Resign Sakit</option>
                                <option value="Tanpa Keterangan">Tanpa Keterangan</option>
                                <option value="Dinyatakan Tidak Sehat">Dinyatakan Tidak Sehat</option>
                                <option value="Alasan Lainnya">Alasan Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan <i>(Isi jika memilih alasan lainnya)</i></label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
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
@endsection