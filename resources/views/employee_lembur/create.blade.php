@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan Kerja Lembur</h4>
            {{ Breadcrumbs::render('pengajuan-lembur') }}
        </div>
        <div class="row">
            <div class="col-md-8">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('employee-lembur.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
							<div class="field_wrapper">
                            <div class="table-responsive">
							<table class="display table-head-bg-primary datatables">
							<thead>
							<tr>
							<th>Tanggal</th>
							<th>Jam Mulai</th>
							<th>Jam Selesai</th>
							<th>Lokasi Kerja</th>
							<th>Keterangan</th>
							<th>Action</th>
							</tr>
							</thead>
							<tbody>
							<tr>
							<td><input type="text" class="form-control datepicker" placeholder="Pilih Tanggal" name="start_date[]" required ></td>
							<td><input type="text" class="form-control start_time" name="start_time[]"></td>
							<td><input type="text" class="form-control end_time" name="end_time[]"></td>
							<td><select name="approval_position[]" class="form-control select2" required>
                                        <option value="WFO">WFO</option>
                                        <option value="WFH">WFH</option>
                                    </select></td>
							<td><textarea class="form-control" name="reason[]" rows="1" required></textarea></td>
							<td><a href="javascript:void(0);" class="btn btn-success btn-md" id="add_button"><i class="fas fa-plus"></i></a></td>
							</tr>
							</tbody>
							</table>
                            </div>
							</div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md" id="add_button" title="Add field"><i class="fas fa-save"></i>Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
			
			<div class="col-md-4">
				<div class="col-md-12">
                    <div class="alert alert-primary">
                        <label class="text-primary"><i class="fas fa-lightbulb"></i> Good to know !</label><br>
						<table class="table-borderless">
						<tr>
						<td valign="top">1.</td>
						<td style="text-align: justify;">Pengajuan kerja lembur untuk mengajukan kelebihan jam kerja di hari kerja maupun di hari libur.</td>
						</tr>
						<tr>
						<td valign="top">2.</td>
						<td style="text-align: justify;">Kelebihan jam kerja yang dimaksud adalah min 2 jam 15 menit, dan lembur pada hari libur adalah min 4 jam.</td>
						</tr>
						<tr>
						<td valign="top">3.</td>
						<td style="text-align: justify;">Pengajuan Kerja Lembur diajukan maksimal H+1.</td>
						</tr>
						<tr>
						<td valign="top">4.</td>
						<td style="text-align: justify;">Pastikan tanggal dan jam pengajuan lembur diajukan dengan benar.</td>
						</tr>
						<tr>
						<td valign="top">5.</td>
						<td style="text-align: justify;">Contoh pengisian:</td>
						</tr>
						<tr>
						<td></td>
						<td style="text-align: justify;">- Pengajuan kelebihan jam kerja pada hari kerja : misal jam mulai 17:00 dan jam selesai 19:15.</td>
						</tr>
						<tr>
						<td></td>
						<td style="text-align: justify;">- Pengajuan kerja lembur pada hari libur : misal jam mulai 08:00 dan jam selesai 12:00.</td>
						</tr>
						</table>
                        </div>
                </div>
            </div>
			
			    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List Pengajuan Lembur Anda</h4>
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
										<th>Lokasi Kerja</th>
										<th>Keterangan</th>
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
											<td class="content">{{ $employee_dl->approval_position }}</td>
											<td class="content">{{ $employee_dl->reason }}</td>
											
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
	Days.setDate(Days.getDate()-7);
	$('.datepicker').datetimepicker({
		format: 'YYYY-MM-DD',
		useCurrent: false,
		minDate: Days
		});


    $('.start_time').datetimepicker({
        format: 'HH:mm'
    });

    $('.end_time').datetimepicker({
        format: 'HH:mm'
    });
	
    function calculateDiff(){
        $.ajax({
            url: '{{ route('employee-leave.calculate') }}',
            type: 'GET',
            dataType: 'JSON',
            data: {
                // 'leave_code': $('select[name=leave_code]').val(),
                'start_date': $('input[name=start_date]').val(),
                'end_date': $('input[name=start_date]').val(),
            },
            beforeSend: function(){
                $('p#total').html('Menghitung hari...');
            },
            success: function(resp){
                $('p#total').html(resp.working_days+' hari kerja dari '+resp.qty_days+' hari');
				$('#total').val(resp.working_days);
				$('#libur').val(resp.qty_days - resp.working_days);				
            },
            error: function(err){
                $('p#total').html('Error menghitung hari');
            }
        });
    }
	
</script>
<script type="text/javascript">
function initializeDatePicker(){
			
			var Days = new Date(); 
			Days.setDate(Days.getDate()-7);
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
				useCurrent: false,
				minDate: Days,
				
            }); 			

			$('.starttime').datetimepicker({
				format: 'HH:mm'
			});

			$('.endtime').datetimepicker({
				format: 'HH:mm'
			});		
     };
$(document).ready(function(){
	
    var maxField = 10; //Input fields increment limitation
    var addButton = $('#add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<tbody><tr>';
    fieldHTML=fieldHTML + '<td><input type="text" class="form-control datepicker" placeholder="Pilih Tanggal" name="start_date[]"></td>';
	fieldHTML=fieldHTML + '<td><input type="text" class="form-control starttime"  name="start_time[]"></td>';
	fieldHTML=fieldHTML + '<td><input type="text" class="form-control endtime"  name="end_time[]"></td>';
	fieldHTML=fieldHTML + '<td><select name="approval_position[]" class="form-control" required><option value="WFO">WFO</option> <option value="WFH">WFH</option></select></td>';
	fieldHTML=fieldHTML + '<td><textarea class="form-control" name="reason[]" rows="1" required></textarea></td>';
    fieldHTML=fieldHTML + '<td><a href="javascript:void(0);" class="remove_button btn btn-danger"><i class="fas fa-minus"></i></a></td>';
    fieldHTML=fieldHTML + '</tr></tbody>'; 
    var x = 1; //Initial field counter is 1
     
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); 
			initializeDatePicker();
			//Add field html
        }
    });
	
	
     
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('').parent('').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>

@endsection
