@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Training</h4>
            {{ Breadcrumbs::render('training') }}
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
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            @can('create-training')
                                <button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Training
                                </button>
                            @endcan
                            @can('import-training')
                            <a class="btn btn-sm btn-warning btn-round ml-2" href="{{ asset('uploads/excel/template-training-2019.xlsx') }}">
                                <i class="fas fa-cloud-download-alt"></i> Unduh Template
                            </a>
                            <button class="btn btn-sm btn-secondary btn-round ml-2" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-file-import"></i> Import
                            </button>
                            @endcan
                            @can('export-training')
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-round btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('training.excel') }}"><i class="far fa-file-excel"></i> Excel</a>
                                        <a class="dropdown-item" href="{{ route('training.csv') }}"><i class="fas fa-database"></i> CSV</a>
                                        <a class="dropdown-item" href="{{ route('training.pdf') }}"><i class="far fa-file-pdf"></i> PDF</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="training-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">ID</th>
                                        <th class="content">Tipe</th>
                                        <th class="content">Category</th>
                                        <th class="content">Nama Training</th>
                                        <th class="content">Vendor</th>
                                        <th class="content">Mulai</th>
                                        <th class="content">Berakhir</th>
                                        <th class="content">Durasi</th>
                                        <th class="content">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trainings as $training)
                                        <tr>
                                            <td class="content">{{ $training->id }}</td>
                                            <td class="content">{{ $training->type }}</td>
                                            <td class="content">{{ $training->category }}</td>
                                            <td class="content">{{ $training->name }}</td>
                                            <td class="content">{{ $training->vendor }}</td>
                                            <td class="content">{{ $training->start_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training->end_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training->duration }} jam</td>
                                            <td class="content">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-bars"></i> Opsi
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="javascript:void(0)" class="dropdown-item" onclick="view_participants({{ $training->id }}, this)"><i class="fas fa-search"></i> Lihat peserta</a>
                                                        <a href="javascript:void(0)" class="dropdown-item" onclick="edit({{ $training->id }}, this)"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                        <a href="javascript:void(0)" class="dropdown-item" onclick="remove({{ $training->id }}, this)"><i class="fas fa-trash-alt"></i> Hapus</a>
                                                    </div>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">History Pengajuan Training</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="training-submission-table" class="display table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th class="content">Opsi</th>
                                        <th class="content">Status</th>
                                        <th class="content">Diajukan Oleh</th>
                                        <th class="content">ID</th>
                                        <th class="content">Tipe</th>
                                        <th class="content">Kategori</th>
                                        <th class="content">Nama</th>
                                        <th class="content">Vendor</th>
                                        <th class="content">Dari</th>
                                        <th class="content">Sampai</th>
                                        <th class="content">Durasi</th>
                                        <th class="content">Biaya</th>
                                        <th class="content">Note</th>
                                        <th class="content">File</th>
                                        <th class="content">Reject Note</th>
                                        <th class="content">Peserta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($training_submissions as $training_submission)
                                        <tr>
                                            <td class="content">
                                                @if ($training_submission->status == 10 || $training_submission->status == 20)
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="doCopy({{ $training_submission->id }}, this)">
                                                        <i class="fa fa-copy"></i> Migrasi</button>
                                                @endif
                                            </td>
                                            <td class="content">{!! status_text($training_submission->status) !!}</td>
                                            <td class="content">{{ $training_submission->submitted_by->fullname }}</td>
                                            <td class="content">{{ $training_submission->id }}</td>
                                            <td class="content">{{ $training_submission->type }}</td>
                                            <td class="content">{{ $training_submission->category }}</td>
                                            <td class="content">{{ $training_submission->name }}</td>
                                            <td class="content">{{ $training_submission->vendor }}</td>
                                            <td class="content">{{ $training_submission->start_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training_submission->end_date->format('d/m/Y') }}</td>
                                            <td class="content">{{ $training_submission->duration }}</td>
                                            <td class="content">{{ to_currency($training_submission->cost, 'IDR') }}</td>
                                            <td class="content">{{ $training_submission->note }}</td>
                                            <td class="content">
                                                @if ($training_submission->file)
                                                    <a href="{{ asset('uploads/training_submissions/'.$training_submission->file) }}">{{ $training_submission->file }}</a>
                                                @else
                                                    Tidak Tersedia
                                                @endif
                                            </td>
                                            <td class="content">{{ $training_submission->reject_note }}</td>
                                            <td class="content">{{ $training_submission->employees->implode('fullname', ',') }}</td>
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
    });

    $('#importModal').find('form').on('submit', function(){
        $(this).find('button[type=submit]').addClass('is-loading').attr('disabled', true);
    });

    var dt = $('#training-table').dataTable({
        "autoWidth": true,
        "stateSave": true,
        "stateDuration": -1,
        "columnDefs": [
          { targets: 'no-sort', orderable: false },
          { targets: 'no-search', searchable: false },
        ],
    }).api();

    var dt2 = $('#training-submission-table').dataTable({
        "autoWidth": true,
        "stateSave": true,
        "stateDuration": -1,
        "columnDefs": [
          { targets: 'no-sort', orderable: false },
          { targets: 'no-search', searchable: false },
        ],
    }).api();

    function create() {
        form.attr('action', '{{ url('training') }}');
        modal.find('.modal-title').text('Tambah Training');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function edit(id, el) {
        form.attr('action', '{{ url('training') }}/'+id);
        $.ajax({
            url: '{{ url('training') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('select[name=type]').val(resp.type);
                form.find('select[name=category]').val(resp.category);
                form.find('input[name=name]').val(resp.name);
                form.find('input[name=vendor]').val(resp.vendor);
                form.find('input[name=start_date]').val(resp.start_date);
                form.find('input[name=end_date]').val(resp.end_date);
                form.find('input[name=duration]').val(resp.duration);
                form.find('textarea[name=notes]').text(resp.notes);
                
                resp.employees.forEach(el => {
                    var newOption = new Option(el.fullname, el.id, true, true);
                    select_participants.append(newOption).trigger('change');
                });
                modal.find('.modal-title').text('Edit Training');
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
            html: 'Data tidak bisa dikembalikan',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ url('training') }}/'+id,
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
                        console.error(error);
                        showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
                    }
                })
            }
        });
    }

    function view_participants(id) {
        var content = $('#participantsModal').find('.modal-body');
        $.ajax({
            url: '{{ route('training.participants') }}',
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function(){
                content.addClass('is-loading');
            },
            data: {
                training_id: id
            },
            success: function(resp){
                content.html(resp.html);
                $('#participantsModal').modal('toggle');
            },
            error: function(err){
                showNotification('danger', 'Terjadi Kesalahan! Silahkan muat ulang.');
            },
            complete: function(){
                content.removeClass('is-loading');
            }
        });
    }
    var select_participants = $('select[id=participants]');
    function init_participants() {
        select_participants.select2({
            dropdownParent: modal,
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
    }
    init_participants();
    modal.on("hidden.bs.modal", function (e) {
        form.trigger('reset');
        validatedForm.resetForm();
        form.attr('action', '{{ route('training.store') }}');
        select_participants.html('').select2('destroy');
        init_participants();
    });
    function doCopy(id, el) {
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            html: 'Data tidak bisa dikembalikan',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya, migrasi!',
            cancelButtonText: 'Jangan',
            showLoaderOnConfirm: true,
            preConfirm: ()=>{
                $(el).addClass('is-loading').attr('disabled', true);
                $.ajax({
                    url: '{{ route('pengajuan.training.migrate') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        'id': id
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

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="participantsModal" tabindex="-1" role="dialog" aria-labelledby="participantsModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Peserta Training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Form Training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('training.store') }}" method="post" id="formModal">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Tipe <span class="required-label">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori <span class="required-label">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="Technical">Technical</option>
                                        <option value="Softskill">Softskill</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Training <span class="required-label">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Vendor <span class="required-label">*</span></label>
                                    <input type="text" class="form-control" name="vendor" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Keterangan</label>
                                    <textarea name="notes" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Dari Tanggal <span class="required-label">*</span></label>
                                    <input type="text" class="form-control datepicker" name="start_date" required>
                                    <small class="form-text text-muted">Format: Tahun-Bulan-Hari</small>
                                </div>
                                <div class="form-group">
                                    <label for="">Sampai Tanggal <span class="required-label">*</span></label>
                                    <input type="text" class="form-control datepicker" name="end_date" required>
                                    <small class="form-text text-muted">Format: Tahun-Bulan-Hari</small>
                                </div>
                                <div class="form-group">
                                    <label for="">Durasi <span class="required-label">*</span></label>
                                    <input type="decimal" class="form-control" name="duration" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Peserta <span class="required-label">*</span></label>
                                    <select name="participants[]" id="participants" class="form-control" multiple style="width:100%" required>
                                    </select>
                                </div>
                            </div>
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
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('training.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Upload file <span class="required-label">*</span></label>
                            <input type="file" accept=".xlsx" name="file" class="form-control" required>
                        </div>
                        <div class="form-check">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="reset_data" name="reset_data" value="true">
                                <label class="custom-control-label" for="reset_data">Reset Existing Data ? <span class="required-label">(Tindakan Berbahaya)</span></label>
                            </div>
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