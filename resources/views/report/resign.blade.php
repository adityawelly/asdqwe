@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Database Kuota Cuti Karyawan Resign</h4>
            {{ Breadcrumbs::render('report-resign') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('export-report-leave')
                                <div class="btn-group ml-auto">
                                    <a class="btn btn-sm btn-success btn-round ml-2" href="{{ route('report.resign.do_resign_export') }}">
                                        <i class="far fa-file-excel"></i> Export Excel
                                    </a>
                                    
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
                                        <th class="content">NIK</th>
                                        <th class="content">Nama Karyawan</th>
                                        <th class="content">Tanggal Mulai</th>
                                        <th class="content">Tanggal Berakhir</th>
                                        <th class="content no-sort no-search">Kuota</th>
                                        <th class="content no-sort no-search">Kuota Terpakai</th>
                                        <th class="content no-sort no-search">Kuota Sebelumnya</th>
										<th class="content no-sort no-search">Sisa Kuota</th>
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
            url: '{{ route('report.resign') }}',
            type: 'POST',
            dataType: 'JSON',
            data: function(d){
                d.employee_no = filterForm.find('select[name=employee_no]').val();
                d.start_date = filterForm.find('input[name=start_date]').val();
                d.end_date = filterForm.find('input[name=end_date]').val();
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
                "data": "employee_no" },
            { "class": "content",
                "data": "fullname" },
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
            { "class": "content",
                "data": "qty" },
			{ "class": "content",
                "data": "used" },
			{ "class": "content",
              "data": "qty_before" },
			{ "class": "content",
              "data": "sisa" },
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
                    <form action="{{ route('report.resign.do_resign_export') }}" method="post" id="exportFormModal">
                        @csrf
                        <div class="form-group">
                            <label for="">Karyawan <span class="required-label">*</span></label>
                            <select name="employee_no" class="form-control" required style="width: 100%">
                                <option value="semua">Semua</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_no }}">{{ $employee->employee_no.'-'.$employee->fullname }}</option>
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