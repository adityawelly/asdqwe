@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Bekerja Dari Rumah (<i>Work From Home </i>)</h4>
            {{ Breadcrumbs::render('pengajuan-wfh') }}
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('employee-wfh.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
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
                                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Ajukan Kepada <span class="required-label">*</span></label>
                                <p>{{ $direct_superior }}</p>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md"><i class="fas fa-save"></i>Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			<div class="col-md-6">
				<div class="col-md-12">
                    <div class="alert alert-primary">
                        <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label>
                            <br>
                            1. Pengajuan Kerja Dari Rumah (Work From Home) diajukan maksimal H+1. <br> 					
							2. Pastikan tanggal dan jam pengajuan kerja dari rumah  diajukan dengan benar. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jam diisikan dengan jam kerja Anda pada saat WFH.<br> 
							3. Isikan bagian keterangan dengan pekerjaan yang Anda lakukan pada saat WFH.								
                        </div>
                </div>
            </div>
			    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List Pengajuan Kerja Dari Rumah Anda</h4>
                            </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table-head-bg-primary datatables" style="width: 100%">
                                <thead>
                                    <tr>
										<th>Status</th>
                                        <th>Nama</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Total</th>
										<th>Keterangan</th>
										<!--
										<th>Aksi</th> -->
                                    </tr>
                                </thead>
                                <tbody>
								@foreach ($employee_dl as $employee_dl)
                                        <tr>
											<td class="content">
											@switch($employee_dl->status)
                                                    @case('new')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                        @break
                                                    @case('apv')
                                                        <span class="badge badge-success">Diterima</span>
                                                        @break
                                                    @case('rjt')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                        @break
                                                    @default
                                                        
                                                @endswitch
											</td>
                                            <td class="content">{{ $employee_dl->fullname }}</td>
                                            <td class="content">{!! tgl_indo($employee_dl->start_date) .'<b> S/D  </b>'.tgl_indo($employee_dl->end_date) !!}</td>
                                            <td class="content">{!! $employee_dl->start_time .'<b> S/D  </b>' .$employee_dl->end_time !!}</td>
                                            <td class="content">{{ $employee_dl->total }} Hari</td>
											<td class="content">{{ $employee_dl->reason }}</td>
											<!-- <td>
											@if($employee_dl->status == 'apv')
											<a href="{{ route('employee-dinas.cetak', ['id' => $employee_dl->id]) }}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak</a>
											@endif
											</td> -->
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
	var Days = new Date(); 
	Days.setDate(Days.getDate()-4);
	$('input[name=start_date]').datetimepicker({
		format: 'YYYY-MM-DD',
		useCurrent: false,
		minDate: Days
		});

    $('input[name=end_date]').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
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
