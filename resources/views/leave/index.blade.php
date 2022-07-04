@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kategori Leave</h4>
            {{ Breadcrumbs::render('cuti') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-leave')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Kategori Leave
                                </button>
                            @endcan
                            @can('export-leave')
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('leave.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="{{ route('leave.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="{{ route('leave.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="leave-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Kode Leave</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Maks Hari</th>
                                        <th>Hitung Libur ?</th>
                                        <th>Mengurangi Cuti ?</th>
                                        <th>Catatan</th>
                                        <th>Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>
                                                <div class="btn-group">
                                                    @can('update-leave')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Ubah" class="btn btn-icon btn-xs btn-primary" onclick="edit('{{ $leave->leave_code }}', this)">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-leave')
                                                        <button type="button" 
                                                            data-toggle="tooltip" data-placement="top" title="Hapus" class="btn btn-icon btn-xs btn-danger" onclick="remove('{{ $leave->leave_code }}', this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td>{{ $leave->leave_code }}</td>
                                            <td>{{ $leave->leave_name }}</td>
                                            <td>{{ $leave_categories->firstWhere('lookup_value', $leave->leave_category)->lookup_desc ?? $leave->leave_category}}</td>
                                            <td>{{ $leave->qty_max }}</td>
                                            <td>{{ $leave->is_holiday_count == 1 ? 'Ya':'Tidak' }}</td>
                                            <td>{{ $leave->is_minus_annual == 1 ? 'Ya':'Tidak' }}</td>
                                            <td>{{ $leave->notes }}</td>
                                            <td>{{ $leave->created_at->format('d-m-Y H:i') }}</td>
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

    var validatedForm = form.validate();

    $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

    var dt = $('#leave-table').dataTable({
        responsive: true,
    }).api();

    function create() {
        form.attr('action', '{{ url('leave') }}');
        modal.find('.modal-title').text('Tambah Kategori Leave');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('leave') }}/'+id);
        $.ajax({
            url: '{{ url('leave') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('input[name=leave_code]').val(resp.leave_code).attr('readonly', true);
                form.find('input[name=leave_name]').val(resp.leave_name);
                form.find('select[name=leave_category]').val(resp.leave_category).trigger('change');
                form.find('textarea[name=notes]').text(resp.notes);
                form.find('input[name=qty_max]').val(resp.qty_max);
                if(resp.is_holiday_count) form.find('input[name=is_holiday_count]').attr('checked', true);
                if(resp.is_minus_annual) form.find('input[name=is_minus_annual]').attr('checked', true);
                modal.find('.modal-title').text('Edit Kategori Leave');
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
    
    function remove(id, el) {
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
                    url: '{{ url('leave') }}/'+id,
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

    modal.on("hidden.bs.modal", function (e) {
        form.trigger('reset');
        validatedForm.resetForm();
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
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
                            <label for="">Kode Leave <span class="required-label">*</span></label>
                            <input type="text" name="leave_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Nama <span class="required-label">*</span></label>
                            <input type="text" name="leave_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Kategori <span class="required-label">*</span></label>
                            <select name="leave_category" class="form-control selectpicker" style="width:100%" required>
                                @foreach ($leave_categories as $opt)
                                    <option value="{{ $opt->lookup_value }}">{{ $opt->lookup_desc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Maks Hari <span class="required-label">*</span></label>
                            <input type="number" name="qty_max" class="form-control" value="0" required>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" name="is_holiday_count">
                                <span class="form-check-sign"> Hitung Libur ?</span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" name="is_minus_annual">
                                <span class="form-check-sign"> Mengurangi Cuti ?</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="">Notes <span class="required-label">*</span></label>
                            <textarea name="notes" cols="30" rows="10" class="form-control" required></textarea>
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