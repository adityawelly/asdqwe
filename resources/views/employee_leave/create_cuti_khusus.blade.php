@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Cuti Khusus</h4>
            {{ Breadcrumbs::render('pengajuan-cuti-khusus') }}
        </div>
        <div class="row">
            <div class="col-md-7">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @csrf
                            <input type="hidden" name="leave_type" value="cuti_khusus">
                            <div class="form-group">
                                <label for="">Kategori <span class="required-label">*</span></label>
                                <select name="leave_id" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($leaves as $leave)
                                        <option value="{{ $leave->id }}">{{ $leave->name }}</option>
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
                                <label for="">Total Hari</label>
                                <p id="total">0 Hari</p>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan / Alasan <span class="required-label">*</span></label>
                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Ajukan Kepada <span class="required-label">*</span></label>
                                <p>{{ $direct_superior }}</p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i> Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Cuti Khusus</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Kuota Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->name }}</td>
                                        <td>{{ $leave->quota }}</td>
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

    $('input[name=start_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: moment().startOf('d')
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
        var a = moment($('input[name=start_date]').val(), 'YYYY-MM-DD');
        var b = moment($('input[name=end_date]').val(), 'YYYY-MM-DD');

        var diff = parseInt(b.diff(a, 'days'))+1;
        
        $('#total').text(diff.toString() + ' Hari');
    }

</script>
@endsection
