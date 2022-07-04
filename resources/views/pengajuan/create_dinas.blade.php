@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Izin Dinas Luar</h4>
            {{ Breadcrumbs::render('pengajuan-dinas') }}
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @csrf
                            <input type="hidden" name="leave_type" value="cuti">
                            <div class="form-group">
                                <label for="">Kategori <span class="required-label">*</span></label>
                                <select name="leave_code" id="lc" class="form-control selectpicker" required>
                                    <option></option>
                                    @foreach ($leaves as $leave)
                                        <option value="{{ $leave->leave_code }}" data-max="{{ $leave->qty_max }}" data-isholiday="{{ $leave->is_holiday_count }}">{{ $leave->leave_name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-text text-primary bg-default" id="leave_text"></span>
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
                url: '{{ route('employee-dinas.store') }}',
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
	
	 //$('#lc').change(function () {	
	 //$('#lc').on('change', function() {
	 $(document.body).on('change',"#lc",function (e) {	 
	 var job =  $('#lc').find(":selected").val();
		if ( job == 'LVANL' )
		{
			var Days = new Date(); 
			Days.setDate(Days.getDate()+7);
			$('input[name=start_date]').datetimepicker({
				format: 'YYYY-MM-DD',
				useCurrent: false,
				minDate: Days
			});
		}
		else
		{
			var Days = new Date(); 
			Days.setDate(Days.getDate()-7);
			$('input[name=start_date]').datetimepicker({
				format: 'YYYY-MM-DD',
				useCurrent: false,
				minDate: Days
			});
		}
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
            },
            error: function(err){
                $('p#total').html('Error menghitung hari');
            }
        });
    }

    $('select[name=leave_code]').on('change', function(e){
        var qty_max = $(this).find(":selected").data('max');
        var is_holiday = $(this).find(":selected").data('isholiday');
        console.log(qty_max);

        if (qty_max > 0) {
            html = "Anda dapat mengajukan cuti maks. "+qty_max+" hari, hari libur "+(is_holiday == 1 ? 'dihitung':'tidak dihitung')+".";
            $('#leave_text').html(html);
        }else{
            $('#leave_text').html('');
        }
    });

</script>
@endsection
