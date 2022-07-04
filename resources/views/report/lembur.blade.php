@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Rekap Kerja Lembur Karyawan</h4>
            {{ Breadcrumbs::render('report-lembur') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Filter Data</h4>
                            <button class="btn btn-sm btn-round ml-auto" data-toggle="collapse" data-target="#collapseTable">
                                <i class="fas fa-sort"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body collapse" id="collapseTable">
                        <form action="" id="filterForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Karyawan</label>
                                        <select name="employee_no" class="form-control selectpicker" style="width: 100%">
                                            <option value="all" selected>Semua</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->registration_number }}">{{ $employee->registration_number.'-'.$employee->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
										<label for="">Status Pengajuan</label>
                                        <select name="status" class="form-control selectpicker" style="width: 100%">
                                            <option value="all" selected>Semua</option>
											<option value="new">Belum Diapprove</option>
											<option value="apv">Diterima</option>
											<option value="rjt">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Mulai</label>
                                        <input type="text" class="form-control datepicker" name="start_date" required placeholder="Pilih Tanggal">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tanggal Akhir</label>
                                        <input type="text" class="form-control datepicker" name="end_date" required placeholder="Pilih Tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm" onclick="reload(this)"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('export-report-leave')
                                <div class="btn-group ml-auto">
                                    <button type="button" class="btn btn-sm btn-round btn-success" data-toggle="modal" data-target="#exportModal">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="report-lembur-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">ID</th>
                                        <th class="content no-sort no-search">Status</th>
                                        <th class="content">NIK</th>
                                        <th class="content">Nama Karyawan</th>
                                        <th class="content">Tanggal Mulai</th>
                                        <th class="content">Tanggal Berakhir</th>
                                        <th class="content no-sort no-search">Jam Mulai</th>
                                        <th class="content no-sort no-search">Jam Selesai</th>
                                        <th class="content no-sort no-search">Keterangan</th>
                                        <th class="content no-sort no-search">Hari Kerja</th>
										<th class="content no-sort no-search">Hari Libur</th>
                                        <th class="content no-sort no-search">Approval Oleh</th>
                                        <th class="content no-sort no-search">Approval Note</th>
                                        <th class="content">Dibuat</th>
										<th class="content">Tanggal Disetujui <br> / Ditolak</th>
                                        <th class="content no-sort no-search">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
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
    var export_modal = $('#exportModal');
    var form = $('#formModal');
    var submitBtn = form.find('button[type=submit]');
    var filterForm = $('#filterForm');
    var dt = $('#report-lembur-table').dataTable({
        "serverSide": true,
        "processing": true,
        "autoWidth": true,
        "stateSave": true,
        "stateDuration": -1,
        "order": [[ 0, "desc" ]],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "pageLength": 50,
        "ajax": {
            url: '{{ route('report.lembur') }}',
            type: 'POST',
            dataType: 'JSON',
            data: function(d){
                d.employee_no = filterForm.find('select[name=employee_no]').val();
                d.start_date = filterForm.find('input[name=start_date]').val();
                d.end_date = filterForm.find('input[name=end_date]').val();
				d.status = filterForm.find('select[name=status]').val();
            },
            beforeSend: function (request){
                request.setRequestHeader('X-CSRF-TOKEN', $('meta[name=csrf-token]').attr('content'));
            }
        },
        "responsive": true,
        "language": {
            search: "_INPUT_",
            searchPlaceholder: "Cari nama...",
        },
        "columnDefs": [
          { targets: 'no-sort', orderable: false },
          { targets: 'no-search', searchable: false },
        ],
        "columns": [
            { "class": "content",
                "data": "id" },
            { "class": "content",
                "data": "status" },
            { "class": "content",
                "data": "employee.registration_number" },
            { "class": "content",
                "data": "employee.fullname" },
            {
                "class": "content",
                "data": "start_date",
                "render": function(data, type, row){
                    return moment(data).format('DD/MM/YYYY')
                }
            },
            {
                "class": "content",
                "data": "end_date",
                "render": function(data, type, row){
                    return moment(data).format('DD/MM/YYYY')
                }
            },
            {
                "class": "content",
                "data": "start_time",
                "render": function(data, type, row){
                    if (!data) {
                        return '-';
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "end_time",
                "render": function(data, type, row){
                    if (!data) {
                        return '-';
                    }
                    return data;
                }
            },
            { "class": "content",
                "data": "reason" },
            {
                "class": "content",
                "data": "total",
                "render": function(data, type, row){
                    return data+" Hari";
                }
            },
			{
                "class": "content",
                "data": "total_libur",
                "render": function(data, type, row){
                    return data+" Hari";
                }
            },
            {
                "class": "content",
                "data": "approved_by.fullname",
                "render": function(data, type, row){
                    if (!data) {
                        return '-';
                    }
                    return data;
                }
            },
            { "class": "content",
                "data": "approval_note" },
            {
                "class": "content",
                "data": "created_at",
                "render": function (data, type, row){
                    return moment(data).format('DD/MM/YYYY')
                }
            },
			{
                "class": "content",
                "data": "updated_at",
                "render": function (data, type, row){
                    return moment(data).format('DD/MM/YYYY')
                }
            },
            {
                "class": "content",
                "data": "action",
                "orderable": false 
            },
        ]
    }).api();

    $(document).on('focus', '.dataTables_filter input', function() {
        $(this).unbind().bind('keyup', function(e) {
            if(e.keyCode === 13) {
                dt.search( this.value ).draw();
            }
        });
    });

    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

    function reload(e) {
        if (dt != undefined) {
            $(e).addClass('is-loading').attr('disabled', true);
            dt.ajax.reload(function(){
                $(e).removeClass('is-loading').attr('disabled', false);
            }, false);
        }
    }

    function delete_lembur(id) {
        if (confirm('Apakah anda yakin ?')) {
            $.ajax({
                url: '{{ route('report.delete_lembur') }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    'id':id
                },
                success: function(resp){
                    showNotification(resp.type, resp.msg);
                    if (dt != undefined && resp.type == 'success') {
                        dt.ajax.reload();
                    }
                },
                error: function(err){
                    console.error(err);
                    showNotification('danger', 'Mohon refresh dan coba kembali, atau hubungi admin');
                }
            });
        }
    }

    var select2_emp_modal = export_modal.find('select[name=employee_no]');
    function init_select2() {
        select2_emp_modal.select2({
            dropdownParent: export_modal,
            theme: "bootstrap",
            placeholder: "Pilih Opsi"
        }).on('change', function() {
            $(this).trigger('blur');
        });
    }

    export_modal.on('shown.bs.modal', function(e){
        if (select2_emp_modal.hasClass('select2-hidden-accessible')) {
            select2_emp_modal.select2('destroy');
        }
        init_select2();
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Export Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('report.lembur.do_lembur_export') }}" method="post" id="exportFormModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Karyawan <span class="required-label">*</span></label>
                            <select name="employee_no" class="form-control" required style="width: 100%">
                                <option value="semua">Semua</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->registration_number }}">{{ $employee->registration_number.'-'.$employee->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Mulai<span class="required-label">*</span></label>
                            <input type="text" class="form-control datepicker" name="start_date" required placeholder="Pilih Tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Akhir<span class="required-label">*</span></label>
                            <input type="text" class="form-control datepicker" name="end_date" required placeholder="Pilih Tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Tipe File <span class="required-label">*</span></label>
                            <select name="tipe" class="form-control selectpicker" required style="width: 100%">
                                <option></option>
                                @foreach ($tipe as $item)
                                    <option value="{{ $item }}">{{ strtoupper($item) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary"><i class="fa fa-save"></i> Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection