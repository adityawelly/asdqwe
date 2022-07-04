@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Input Ijin</h4>
            {{ Breadcrumbs::render('pengajuan-ijin') }}
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('layouts.partials.alert')
                <div class="alert alert-info">
                    <b>Semua input disini akan auto approved, dan menghiraukan limit -6 hari</b>
                </div>
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-sm btn-secondary btn-round" data-target="#pageModal" data-toggle='modal'>
                            <i class="fa fa-upload"></i> Upload Cuti Bersama</button>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @csrf
                            <input type="hidden" name="leave_type" value="direct">
                            <div class="form-group">
                                <label for="">Nama Karyawan</label>
                                <select name="employee_id" class="form-control">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Kategori <span class="required-label">*</span></label>
                                <select name="leave_code" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($leaves as $leave)
                                        <option value="{{ $leave->leave_code }}">{{ $leave->leave_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Mulai <span class="required-label">*</span></label>
                                <input type="text" class="form-control" placeholder="Pilih Tanggal" name="start_date" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Berakhir <span class="required-label">*</span></label>
                                <input type="text" class="form-control" placeholder="Pilih Tanggal" name="end_date" required disabled autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="">Jam Masuk</label>
                                <input type="text" class="form-control" name="end_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Jam Keluar</label>
                                <input type="text" class="form-control" name="start_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Total Hari</label>
                                <p id="total">0 Hari</p>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan / Alasan <span class="required-label">*</span></label>
                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i> Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="employee_quota">
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var originalForm = $('#employee_leave_form');

    originalForm.validate({
        ignore: ':hidden, .select2-input, .select2-focusser',
        submitHandler: function(form){
            var btnSubmit = originalForm.find('button[type=submit]');
            form = originalForm;
            $.ajax({
                url: '{{ route('employee-leave.store') }}',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                beforeSend: function(){
                    btnSubmit.attr('disabled', true).addClass('is-loading');
                },
                success: function(resp){
                    if (resp.error) {
                        showNotification('danger', resp.msg);
                        btnSubmit.removeClass('is-loading').attr('disabled', false);
                    }else{
                        location.assign(resp.redirect);
                    }
                },
                error: function(error){
                    if (error.status == 422) {
                        showErrorNotification(error.responseJSON.errors);
                    }else{
                        showNotification('danger', 'Terjadi kesalahan silahkan refresh dan coba lagi');
                    }
                    btnSubmit.removeClass('is-loading').attr('disabled', false);
                }
            });
        }
    });

    $('select[name=employee_id]').select2({
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

    $('input[name=start_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        // minDate: moment().startOf('d')
    });

    $('input[name=end_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false
    });

    $('input[name=start_time]').datetimepicker({
        format: 'HH:mm'
    });

    $('input[name=end_time]').datetimepicker({
        format: 'HH:mm'
    });

    $('input[name=start_date]').on('dp.change', function(e){
        $('input[name=end_date]').attr('disabled', false);
        $('input[name=end_date]').data("DateTimePicker").minDate(moment(e.date).startOf('d'));
        if ($('input[name=end_date]').val() != '') {
            calculateDiff();
        }
    });

    $('input[name=end_date]').on('dp.change', function(e){
        $('input[name=start_date]').data("DateTimePicker").maxDate(moment(e.date).startOf('d'));
        calculateDiff();
    });

    function calculateDiff(){
        $.ajax({
            url: '{{ route('employee-leave.calculate_direct') }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                // 'leave_code': $('select[name=leave_code]').val(),
                'start_date': $('input[name=start_date]').val(),
                'end_date': $('input[name=end_date]').val(),
            },
            beforeSend: function(){
                $('p#total').html('Menghitung hari...');
            },
            success: function(resp){
                $('p#total').html(resp.working_days+' hari kerja dari '+resp.qty_days+' hari');
            },
            error: function(err){
                $('p#total').html('Error menghitung hari');
            }
        });
    }

    $('select[name=employee_id]').on('change', function(){
        var id = $(this).val();

        $.ajax({
            url: '{{ route('employee-leave.load_quota_cuti') }}',
            type: 'GET',
            dataType: 'html',
            data: {
                employee_id: id,
            },
            beforeSend: function(){
                $('#employee_quota').html('Mengambil Detail...');
            },
            success: function(resp){
                $('#employee_quota').html(resp);
            },
            error: function(err){
                $('#employee_quota').html('Error mengambil detail quota cuti');
            }
        });
    });
    
    $('select[name=leave_id]').on('change', function(){
        if ($(this).find(':selected').text().trim() == 'Ijin Setengah Hari') {
            $('input[name=start_time]').attr('disabled', false);
            $('input[name=end_time]').attr('disabled', false);
        }else{
            $('input[name=start_time]').attr('disabled', true);
            $('input[name=end_time]').attr('disabled', true);
        }
    });

    $('#formModal').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

</script>
@endsection

@section('modals')
<div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Import Cuti Bersama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee-leave.cuti_upload') }}" method="post" id="formModal" enctype="multipart/form-data">
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
                                    <li><a href="{{ asset('uploads/excel/template-cuti-upload.xlsx') }}">Download Template Cuti Bersama</a></li>
                                    <li>Ekstensi file .xlsx</li>
                                    <li>Gunakan format text untuk NIK Karyawan</li>
                                    <li>Kolom yang diwarna hijau opsional</li>
                                    <li>Format tanggal menggunakan dd/mm/yyyy</li>
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
@endsection