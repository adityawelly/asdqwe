@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Karyawan</h4>
            {{ Breadcrumbs::render('karyawan') }}
        </div>
        <div class="row">
            @if (session('import_error'))
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <h4>Import gagal !</h4>
                        @foreach (session('import_error') as $failure)
                            @foreach ($failure->errors() as $error)
                                <span class="badge badge-danger">Error</span> baris <strong>{{ $failure->row() }}</strong> kolom <strong>{{ $failure->attribute() }}</strong>. {{ $error }}<br>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif
            @hasanyrole('HCMTeam|Personnel')
            <!--
			<div class="col-md-3">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Filter Karyawan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="form-group">
                                <label for="">Departemen</label>
                                <select name="department_id" class="form-control selectpicker">
                                    <option value="all" selected>Semua</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department_code.'-'.$department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Grade Title</label>
                                <select name="grade_title_id" class="form-control selectpicker">
                                    <option value="all" selected>Semua</option>
                                    @foreach ($grade_titles as $grade_title)
                                        <option value="{{ $grade_title->id }}">{{ $grade_title->grade_title_code.'-'.$grade_title->grade_title_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Lokasi Kerja</label>
                                <select name="company_region_id" class="form-control selectpicker">
                                    <option value="all" selected>Semua</option>
                                    @foreach ($company_regions as $company_region)
                                        <option value="{{ $company_region->id }}">{{ $company_region->region_city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm" onclick="reload(this)"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			-->
            @endhasanyrole
            @hasanyrole('HCMTeam|Personnel')
            <div class="col-md-12">
            @else
            <div class="col-md-12">
            @endhasanyrole
                @if ($unset_superiors)
                    <div class="alert alert-danger" role="alert">
                        <b>Perhatian !</b> karyawan berikut belum di-set atasan
                        <br><br>
                        @foreach ($unset_superiors as $item)
                            {!! $loop->iteration.'. <b>'.$item->registration_number.'</b> - '.$item->fullname !!}
                            <br>
                        @endforeach
                    </div>
                @endif
                <div class="card">
                    <div class="card-header" style="background-color: darkgrey;overflow-x:auto;">
                        <div class="btn-group">
                            @can('create-employee')
                                <a class="btn btn-xs btn-primary" href="{{ route('employee.create') }}">
                                    <i class="fa fa-plus"></i>
                                    Tambah Karyawan
                                </a>
                            @endcan
                            @can('import-employee')
                                <a class="btn btn-xs btn-warning" href="{{ asset('uploads/excel/template-2019.xlsx') }}">
                                    <i class="fas fa-cloud-download-alt"></i> Unduh Template
                                </a>
                                <button class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#pageModal">
                                    <i class="fas fa-file-import"></i> Import
                                </button>
                                <a class="btn btn-xs btn-warning" href="{{ asset('uploads/excel/template-hk.xlsx') }}">
                                    <i class="fas fa-cloud-download-alt"></i> Unduh Template HK
                                </a>
                                <button class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#importModal">
                                    <i class="fas fa-file-import"></i> Import HK
                                </button>
								<a class="btn btn-xs btn-warning" href="{{ asset('uploads/excel/template-update-rek.xlsx') }}">
                                    <i class="fas fa-cloud-download-alt"></i> Unduh Temp Upd-Rek
                                </a>
								<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#import_rekModal">
                                    <i class="fas fa-file-import"></i> Update Rek
                                </button>
                            @endcan
                            @can('export-employee')
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('employee.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                    <a class="dropdown-item" href="{{ route('employee.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                    <a class="dropdown-item" href="{{ route('employee.pdf') }}" target="_blank"><i class="far fa-file-pdf"></i> Print & PDF</a>
                                </div>
                            </div>
                            @endcan
                            @can('read-resign')
                                <a class="btn btn-xs btn-default" href="{{ route('employee.retirement') }}"><i class="fas fa-user-slash"></i> Database Resign</a>
                            @endcan
                            <button class="btn btn-xs btn-grey" onclick="reload(this)"><i class="fas fa-redo-alt"></i> Refresh</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employee-table" class="display table-head-bg-primary">
                                <thead>
                                    <tr>
                                        @can('restore-employee')
                                            <th class="content">Status</th>
                                        @endcan
                                        <th class="content">Aksi</th>
                                        <th class="content">ID</th>
                                        <th class="content">NIK</th>
                                        <th class="content">Mulai Kerja</th>
                                        <th class="content">Nama</th>
                                        <th class="content no-sort no-search">Divisi</th>
                                        <th class="content no-sort no-search">Department</th>
                                        <th class="content no-sort no-search">Grade Title</th>
                                        <th class="content no-sort no-search">Job Title</th>
                                        <th class="content no-sort no-search">Regional</th>
                                        <th class="content no-sort no-search">Hari Kerja</th>
										<th class="content no-sort no-search">DiInput Oleh</th>
                                    </tr>
                                </thead>
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
    var resignModal = $('#resignModal');
    var form = $('#formModal');
    var resignForm = $('#formResignModal');
    var progresscard = $('.progress-card');
    var progressbar = $('.progress-bar');
    var submitBtn = form.find('button[type=submit]');

    var validatedForm = form.validate();
    var validatedResignForm = resignForm.validate();

    var dt = $('#employee-table').dataTable({
        "serverSide": true,
        "processing": true,
        "autoWidth": true,
        "stateSave": true,
        "stateDuration": -1,
        "oLanguage": {
            sProcessing: "Mengambil data..."
        },
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "ajax": {
            url: '{{ route('employee.datatable') }}',
            type: 'POST',
            dataType: 'JSON',
            data: function(d){
                d.department_id = $('select[name=department_id]').val();
                d.grade_title_id = $('select[name=grade_title_id]').val();
                d.company_region_id = $('select[name=company_region_id]').val();
            },
            beforeSend: function (request){
                request.setRequestHeader('X-CSRF-TOKEN', $('meta[name=csrf-token]').attr('content'));
            }
        },
        "responsive": false,
        "language": {
            search: "_INPUT_",
            searchPlaceholder: "Cari NIK atau nama...",
        },
        "columnDefs": [
          { targets: 'no-sort', orderable: false },
          { targets: 'no-search', searchable: false },
        ],
        "columns": [
            @can('restore-employee')
            { "class": "content",
                "data": "status", "orderable": false },
            @endcan
            { "class": "content",
                "data": "action", "orderable": false },
            { "class": "content",
                "data": "id" },
            { "class": "content",
                "data": "registration_number" },
            {
                "class": "content",
                "data": "date_of_work",
                "render": function(data, type, row){
                    return moment(data).format('DD/MM/YYYY');
                }
            },
            { "class": "content",
                "data": "fullname" },
            {
                "class": "content",
                "data": "division.division_name",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "department.department_name",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "grade_title.grade_title_name",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "job_title.job_title_name",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "company_region.region_city",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data;
                }
            },
            {
                "class": "content",
                "data": "hari_kerja.hk",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-danger'>Kosong</span>";
                    }
                    return data+' hari';
                }
            },
			{
                "class": "content",
                "data": "created_by.fullname",
                "render": function (data, type, row){
                    if (!data) {
                        return "<span class='badge badge-success'>Admin</span>";
                    }
                    return data;
                }
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

    $('select[name=direct_superior]').select2({
        dropdownParent: $('#superiorModal'),
        minimumInputLength: 3,
        theme: 'bootstrap',
        placeholder: 'Pilih Opsi',
        ajax: {
            delay: 500,
            url: '{{ route('employee.employee_select_data') }}',
            type: 'POST',
            dataType: 'JSON',
            processResults: function (data, params){
                return {
                    results: data
                };
            },
            error: function(err){
                console.error(err);
            }
        }
    });

    form.on('submit', function(e){
        e.preventDefault();
        if (validatedForm.valid()) {
            submitFile();
        }
    });

    resignForm.on('submit', function(e){
        e.preventDefault();
        if (validatedResignForm.valid()) {
            submitResign();
        }
    });

    function resign(id, el) {
        resignForm.find('input[name=employee_id]').val(id);
        resignModal.modal('toggle');
    }

    function submitResign() {
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Tidak',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $.ajax({
                    url: resignForm.attr('action'),
                    type: 'POST',
                    dataType: 'JSON',
                    data: resignForm.serialize(),
                    beforeSend: function(){
                        resignForm.find('button[type=submit]').attr('disabled', true).addClass('is-loading');
                    },
                    success: function(resp){
                        showNotification(resp.type, resp.msg);
                        dt.ajax.reload();
                        resignModal.modal('toggle');
                    },
                    error: (error)=>{
                        this.close();
                        console.error(error);
                        if (error.status == 422) {
                            showErrorNotification(error.responseJSON.errors);
                        }else{
                            showNotification('error', 'Terjadi kesalahan silahkan refresh dan coba lagi');
                        }
                    },
                    complete: function(){
                        resignForm.find('button[type=submit]').attr('disabled', false).removeClass('is-loading');
                    }
                });
            }
        });
    }

    function submitFile() {
        var formData = new FormData();

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('file', form.find('input[type=file]')[0].files[0]);

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function(){
                submitBtn.attr('disabled', true).addClass('is-loading');
                progresscard.show();
                progressbar.addClass('bg-secondary').removeClass('bg-danger bg-success');
                progressbar.attr("style", "width:0%");
                progresscard.find('.fw-bold').html("0%");
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                progressbar.attr("style", "width:" + percentComplete + "%");
                                progresscard.find('.fw-bold').html(percentComplete + "%");
                            }
                        }, false);
                        return xhr;
            },
            success: function(data)
            {
                location.reload();
                // form.find('input[type=file]').val('');
                // progressbar.addClass('bg-success').removeClass('bg-secondary');
                // modal.modal('toggle');
                // dt.ajax.reload();
                // sendNotification('success', 'File berhasil diimport, tabel telah diperbarui');
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                form.find('input[type=file]').val('');
                progressbar.addClass('bg-danger').removeClass('bg-secondary');
                if (jqXHR.status == 422) {
                    showErrorNotification(jqXHR.responseJSON.errors);
                }else{
                    swal({
                        type: 'error',
                        title: 'Terjadi Kesalahan !',
                        text: 'Upload gagal, silahkan refresh coba lagi'
                    });
                }
            },
            complete: function(){
                submitBtn.removeClass('is-loading').attr('disabled', false);
            }
        });
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
                $.ajax({
                    url: '{{ route('employee.destroy', '') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        _method: 'DELETE',
                        force: flag
                    },
                    success: function(resp){
                        showNotification(resp.type, resp.msg);
                        dt.ajax.reload();
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
                    url: '{{ route('employee.restore', '') }}/'+id,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(resp){
                        showNotification(resp.type, resp.msg);
                        dt.ajax.reload();
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

    function reload(e) {
        if (dt != undefined) {
            $(e).addClass('is-loading').attr('disabled', true);
            dt.ajax.reload(function(){
                $(e).removeClass('is-loading').attr('disabled', false);
            }, false);
        }
    }
    
    var superiorForm = $('#formSuperiorModal');
    function set_superior(id, el) {
        superiorForm.find('input[name=employee_id]').val(id);
        $.ajax({
            url: '{{ url('employee/get_direct_superior') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            success: function(resp){
                if (resp.nik != null) {
                    superiorForm.find('p').html(resp.nik+'-'+resp.fullname);
                }
            }
        })
        $('#superiorModal').modal('toggle');
    }

    var teams = [];
    function view_teams(id, modal){
        var content = $('#teamModal').find('.modal-body');
        teams.push(id);
        $.ajax({
            url: '{{ route('employee.get_teams') }}',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function(){
                content.addClass('is-loading');
            },
            data: {
                direct_superior : id
            },
            success: function (resp){
                $('.superior_name').text(resp.superior);
                content.html(resp.html);
            },
            complete: function(xhr){
                content.removeClass('is-loading');
            },
            error: function(err){
                showNotification('danger', 'Terjadi Kesalahan!<br>Silahkan reload.');
            }
        });
        var i = teams.indexOf(id);
        if (teams[i-1] != undefined) {
            $('#teamModal').find('.modal-footer').find('button').attr('onclick', 'view_teams('+teams[i-1]+', false)').attr('disabled', false);
        }
        
        if (modal) {
            $('#teamModal').modal('toggle');
        }
    }

    $('#superiorModal').on('hidden.bs.modal', function(e){
        superiorForm.find('p').html('');
        superiorForm.find('select[name=direct_superior]').val('');
        superiorForm.find('input[name=employee_id]').val('');
    });
    superiorForm.on('submit', function(e){
        e.preventDefault();
        if (superiorForm.validate().valid()) {
            $.ajax({
                url: superiorForm.attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: superiorForm.serialize(),
                success: function(resp){
                    showNotification(resp.status, resp.msg);

                    $('#superiorModal').modal('toggle');
                },
                error: function(err){
                    console.error(err);
                    showNotification('error', 'Mohon refresh dan coba kembali, atau hubungi admin');
                }
            })
        }
    });
    $('#importModal form').on('submit', function(){
        $(this).find('button[type=submit]').addClass('is-loading').attr('disabled', true);
    });
</script>
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="superiorModal" tabindex="-1" role="dialog" aria-labelledby="superiorModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="superior-modal">Form Superior</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.update_direct_superior') }}" method="post" id="formSuperiorModal">
                        @csrf
                        <input type="hidden" name="employee_id">
                        <div class="form-group">
                            <label for="">Superior Sekarang</label>
                            <p></p>
                        </div>
                        <div class="form-group">
                            <label for="">Pilih Superior (Atasan) <span class="required-label">*</span></label>
                            <select name="direct_superior" class="form-control" style="width:100%" required>
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
    <!-- Modal -->
    <div class="modal fade" id="teamModal" tabindex="-1" role="dialog" aria-labelledby="teamModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="team-modal">List tim <span class="superior_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning" onclick="" disabled="disabled"><i class="fas fa-chevron-left"></i> Kembali</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="resignModal" tabindex="-1" role="dialog" aria-labelledby="resignModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="resign-modal">Form Resign Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.resign') }}" method="post" id="formResignModal">
                        @csrf
                        <input type="hidden" name="employee_id">
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
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Upload file import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.import') }}" method="post" id="formModal" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">File <span class="required-label">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".xlsx" required>
                        </div>
                        <div class="form-group">
                            <div class="progress-card" style="display:none">
                                <div class="progress-status">
                                    <span class="text-muted">Status</span>
                                    <span class="text-muted fw-bold"> 0%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="card">
                                <div class="card-body">
                                    <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label>
                                    <ol>
                                        <li>Ukuran maksimal 2MB</li>
                                        <li>Ekstensi file .xlsx</li>
                                        <li>Gunakan awalan (') petik satu untuk kolom berisi tanggal dan angka</li>
                                        <li>Kolom yang diwarna hijau opsional</li>
                                        <li>Format tanggal menggunakan dd/mm/yyyy</li>
                                        <li>Import tidak berhasil jika ada satu saja kesalahan dalam mengisi kolom template</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Import HK Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Import HK Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee-hk.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Upload file <span class="required-label">*</span></label>
                            <input type="file" accept=".xlsx" name="file" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<!-- Import No Rekening Modal -->
	<div class="modal fade" id="import_rekModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Update Data Rekening Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employee.import_rek') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Upload file <span class="required-label">*</span></label>
                            <input type="file" accept=".xlsx" name="file" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection