@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Cuti</h4>
            {{ Breadcrumbs::render('pengajuan-cuti') }}
        </div>
        <div class="row">
            <div class="col-md-7">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="">Kategori <span class="required-label">*</span></label>
                                <select name="leave_id" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($leaves as $leave)
                                        <option {{ $leave->id == $employee_leave->leave_id ? 'selected':'' }} value="{{ $leave->id }}">{{ $leave->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Mulai <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="start_date" value="{{ $employee_leave->start_date }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Berakhir <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="end_date" value="{{ $employee_leave->end_date }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">Total Hari</label>
                                <input type="text" class="form-control" value="5" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan / Alasan <span class="required-label">*</span></label>
                                <textarea class="form-control" name="reason" rows="3" required>{{ $employee_leave->reason }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i> Ubah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Saldo Cuti Anda</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Saldo</th>
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
    var originalForm = $('#employee_leave_form');
    var btnSubmit = originalForm.find('button[type=submit]');

    originalForm.validate({
        ignore: ':hidden, .select2-input, .select2-focusser',
        submitHandler: function(form){

            form = originalForm;
            $.ajax({
                url: '{{ route('employee-leave.update', $employee_leave->id) }}',
                type: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
                beforeSend: function(){
                    btnSubmit.attr('disabled', true).addClass('is-loading');
                },
                success: function(resp){
                    location.assign(resp.redirect);
                },
                error: function(error){
                    if (error.status == 422) {
                        showErrorNotification(error.responseJSON.errors);
                    }else{
                        showNotification('error', 'Terjadi kesalahan silahkan refresh dan coba lagi');
                    }
                    buttonSubmit.removeClass('is-loading').attr('disabled', false);
                }
            });
        }
    });

    $('input[name=start_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: moment()
    });

    $('input[name=end_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: moment()
    });
</script>
@endsection
