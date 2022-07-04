@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Training</h4>
            {{ Breadcrumbs::render('pengajuan-training') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Data</h4>
                            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#pageModal">
                                <i class="fa fa-plus"></i>
                                Tambah Pengajuan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-head-bg-primary" id="training-submissions-table">
                                <thead>
                                    <tr>
                                        <th class="content">Opsi</th>
                                        <th class="content">Status</th>
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
                                                @if (in_array($training_submission->status, [5,15,25]))
                                                    <div class="btn-group-vertical">
                                                        <button type="button" class="btn btn-xs" onclick="edit({{ $training_submission->id }}, this)"><i class="fas fa-pencil-alt"></i> Edit</button>
                                                        <button type="button" class="btn btn-danger btn-xs" onclick="remove({{ $training_submission->id }}, this)"><i class="fas fa-times-circle"></i> Hapus</button>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="content">{!! status_text($training_submission->status) !!}</td>
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
                                                    <a href="{{ public_path('uploads/training_submissions/'.$training_submission->file) }}">{{ $training_submission->file }}</a>
                                                @else
                                                    Tidak Tersedia
                                                @endif
                                            </td>
                                            <td class="content">{{ $training_submission->reject_note }}</td>
                                            <td class="content">{!! $training_submission->employees->implode('fullname', ',') !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var dt = $('#training-submissions-table').dataTable({
        // responsive: true,
    }).api();

    var modal = $('#pageModal');
    var form = modal.find('form');

    var select_participants = $('select[id=participants]');
    init_participants();
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
    $('#pengajuan_training').on('submit', function(){
        $(this).find('button[type=submit]').addClass('is-loading').attr('disabled', true);
    });
    function edit(id, el) {
        $.ajax({
            url: '{{ route('pengajuan.training.edit', '') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function (resp){
                form.attr('action', '{{ route('pengajuan.training.update', '') }}/'+id);

                form.find('select[name=type]').val(resp.type);
                form.find('select[name=category]').val(resp.category);
                form.find('input[name=name]').val(resp.name);
                form.find('input[name=vendor]').val(resp.vendor);
                form.find('input[name=start_date]').val(resp.start_date);
                form.find('input[name=end_date]').val(resp.end_date);
                form.find('input[name=duration]').val(resp.duration);
                form.find('textarea[name=notes]').text(resp.notes);
                console.log(resp.employees);
                resp.employees.forEach(el => {
                    var newOption = new Option(el.fullname, el.id, true, true);
                    select_participants.append(newOption).trigger('change');
                });
                modal.modal('toggle');
            },
            error: function(error){
                console.log(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            },
            complete: function(){
                $(el).attr('disabled', false).removeClass('is-loading');
            }
        });
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
                    url: '{{ route('pengajuan.training.delete', '') }}/'+id,
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
    modal.on('hidden.bs.modal', function(e){
        form.trigger('reset');
        form.attr('action', '{{ route('pengajuan.training.submit') }}');
        select_participants.html('').select2('destroy');
        init_participants();
    });
</script>
@endsection

@section('modals')
<!-- Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Form Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pengajuan.training.submit') }}" method="POST" id="pengajuan_training" enctype="multipart/form-data">
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
                                <label for="">Biaya Training (Rp.)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="text" class="form-control money-mask" name="cost" placeholder="x.xxx.xxx">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="notes" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Peserta <span class="required-label">*</span></label>
                                <select name="participants[]" id="participants" class="form-control" multiple style="width:100%">
                                </select>
                            </div>
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
                                <label for="">Durasi (dalam jam) <span class="required-label">*</span></label>
                                <input type="number" class="form-control" name="duration" required step="any">
                                <span class="form-text">Mohon tulis hanya angka cth : 4.5</span>
                            </div>
                            <div class="form-group">
                                <label for="">File Pendukung</label>
                                <input type="file" class="form-control" name="file" accept=".pdf,.png,.jpg">
                                <small class="form-text text-muted">*Tipe:jpg/png/pdf *Maks:1MB</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-md pull-right"><i class="fas fa-save"></i> Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection