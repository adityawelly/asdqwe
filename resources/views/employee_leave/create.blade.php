@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Izin</h4>
            {{ Breadcrumbs::render('pengajuan-ijin') }}
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST" id="employee_leave_form">
                            @csrf
                            <input type="hidden" name="leave_type" value="ijin">
                            <div class="form-group">
                                <label for="">Kategori <span class="required-label">*</span></label>
                                <select name="leave_code" id="lc" class="form-control selectpicker" required>
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
                                <label for="">Jam Mulai <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="start_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Jam Akhir <span class="required-label">*</span></label>
                                <input type="text" class="form-control" name="end_time" autocomplete="off" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Total Hari</label>
                                <p id="total">0 Hari</p>
                            </div>
							<!--
							<div class="form-group">
                                <label for="">Total Jam</label>
                                <input type="text" class="form-control" id="jam" name="jam" value="0" readonly>
                            </div>
							-->
                            <div class="form-group">
                                <label for="">Keterangan / Alasan <span class="required-label">*</span></label>
                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Ajukan Kepada <span class="required-label">*</span></label>
                                <p>{{ $direct_superior }}</p>
                            </div>
							@if ($quota->msg->qty <= 0)						
							<div class="form-group" id="pg" style="display:none">
                                <label for="">Potong Gaji <span class="required-label">*</span></label>
                                <p><input type="checkbox" name="potong_gaji" value="1"> Ya</p>
                            </div>
							@endif
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i> Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-primary">
                            <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label>
                            <br>
                            Mohon <b>izin setengah hari</b> isi jam izin anda. Contoh : <br>
                            1. Datang terlambat, sampai kantor jam 11, maka isi 08:00 dan 11:00 <br>
                            2. Pulang cepat, pulang jam 13, maka isi 13:00 dan 17:00 (Jam pulang anda)
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-primary">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">Rekap cuti periode 
                                        @if ($quota->status == 'success')
                                            {{ date('d M Y', strtotime($quota->msg->start_date)) }} s/d {{ date('d M Y', strtotime($quota->msg->end_date)) }}
                                        @else
                                            N/A
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="content">Tipe</th>
                                                <th class="content">Kuota</th>
                                                <th class="content">Terpakai</th>
                                                <th class="content">Sisa Periode Lalu</th>
                                                <th class="content">Sisa Hak Cuti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($quota->status == 'success')
                                                <tr>
                                                    <td class="content">Existing</td>
                                                    <td class="content">{{ $quota->msg->qty_gen }}</td>
                                                    <td class="content">{{ $quota->msg->used }}</td>
                                                    <td class="content">{{ $quota->msg->qty_before }}</td>
                                                    <td class="content">{{ $quota->msg->qty }}</td>
                                                </tr>
                                                @if ($quota->msg->qty_extend != null)
                                                    <tr>
                                                        <td class="content">Extend</td>
                                                        <td class="content">{{ $quota->msg->qty_extend }}</td>
                                                        <td class="content">{{ $quota->msg->used_extend }}</td>
                                                        <td class="content">-</td>
                                                        <td class="content">
                                                            @if ($quota->msg->ext_sts == 1)
                                                                {{ $quota->msg->qty_extend - $quota->msg->used_extend }} 
                                                                (s/d {{ date('d-m-Y', strtotime($quota->msg->expired_at)) }})
                                                            @else
                                                                <span class="badge badge-danger">Expired</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td colspan="5" class="content">{{ $quota->msg }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

	//var Days = new Date();
	
	$(document).ready(function(){
	 $('#lc').change(function () {
	 var job =  $('#lc').val();
		if ( job == 'LVUL' )
		{
			$("#pg").show();
		}
		else
		{
			$("#pg").hide();
		}
	 })
	});
	
    $('input[name=start_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
    });

    $('input[name=end_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false
    });

	 $('input[name=start_time]').mask('00:00' , {reverse: true});
	 $('input[name=end_time]').mask('00:00' , {reverse: true});
   // $('input[name=start_time]').datetimepicker({
   //     format: 'HH:mm'
  //  });

    //$('input[name=end_time]').datetimepicker({
       // format: 'HH:mm'
    //});

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
/*	
	function calculateTime(){
		
		var waktuMulai = $('input[name=start_time]').val(),
          waktuSelesai = $('input[name=end_time]').val(),
       hours = waktuSelesai.split(':')[0] - waktuMulai.split(':')[0],
          minutes = waktuSelesai.split(':')[1] - waktuMulai.split(':')[1];
 
		  if (waktuMulai <= "12:00" && waktuSelesai >= "13:00"){
			a = 1;
		  }else {
			a = 0;
		  }
		  minutes = minutes.toString().length<2?'0'+minutes:minutes;
		  if(minutes<0){ 
			  hours--;
			  minutes = 60 + minutes;        
		  }
		  hours = hours.toString().length<2?'0'+hours:hours;
		  $('#jam').val(hours-a);
    }
    */
    $('select[name=leave_code]').on('change', function(){
        if ($(this).find(':selected').text().trim() == 'Ijin Setengah Hari') {
            $('input[name=start_time]').attr('disabled', false).attr('required', true);
            $('input[name=end_time]').attr('disabled', false).attr('required', true);
        }else{
            $('input[name=start_time]').attr('disabled', true);
            $('input[name=end_time]').attr('disabled', true);
        }
    });

</script>
@endsection
