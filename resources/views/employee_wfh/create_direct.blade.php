@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Input Bekerja Dari Rumah (<i>Work From Home</i>)</h4>
            {{ Breadcrumbs::render('pengajuan-wfh') }}
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('layouts.partials.alert')
                <div class="alert alert-info">
                    <b>Semua input disini akan auto approved</b>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Karyawan</label>
                                <select name="employee_no" class="form-control selectpicker">
                                    <option></option>
									@foreach ($employees as $item)
                                      <option value="{{ $item->registration_number }}">{{ $item->registration_number.'-'.$item->fullname }}</option>
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
								<input type="hidden" class="form-control" name="total" id="total">
								<input type="hidden" class="form-control" name="libur" id="libur">
                                <p id="total">0 Hari</p>
                            </div>
							<div class="form-group">
                                <label for="">Jam Mulai</label>
                                <input type="text" class="form-control" name="start_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Jam Selesai</label>
                                <input type="text" class="form-control" name="end_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan<span class="required-label">*</span></label>
                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i>Simpan</button>
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
                url: '{{ route('employee-wfh.store_direct') }}',
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
            url: '{{ route('employee-leave.calculate') }}',
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
				$('#total').val(resp.working_days);
				$('#libur').val(resp.qty_days - resp.working_days);
				
					if (resp.working_days == 1){
						$('input[name=start_time]').attr('disabled', false).attr('required', true);
						$('input[name=end_time]').attr('disabled', false).attr('required', true);
					}else{
						$('input[name=start_time]').attr('disabled', true);
						$('input[name=end_time]').attr('disabled', true);
					}
            },
            error: function(err){
                $('p#total').html('Error menghitung hari');
            }
        });
    }
</script>
@endsection
