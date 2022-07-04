@extends('layouts.app')
@section('content')
<body>
<div class="content animated fadeIn">
        <div class="card-body">	
		@foreach ($pengajuan as $xx)
        <h4 class="page-title" >SURAT KEPUTUSAN ({{ $xx->sk_no }})</h4>
		@endforeach	
        <div class="col-md-12">
			@include('layouts.partials.alert')
                <div class="card">	
					<form action="{{ route('pengajuan.fpk.submit_edit_sk') }}" method="POST" id="pengajuan_fpk" enctype="multipart/form-data">
					@csrf
                    <div class="card-body">
                        <div class="row">
							<input type="hidden" name="ReqId" value="{{ $xx->ReqId }}">
							<table width=100%> 
							<tr>
							<td valign="top"  width=18% >Menimbang</td>
							<td valign="top" width=3% >:</td>
							@foreach ($isi_sk as $isi)
							<td width=70%><textarea name="iheader" cols="30" rows="4" value="{!! $isi->header !!}" class="form-control">{!! $isi->header !!}</textarea></td>
							@endforeach	
							</tr>
							<tr>
							<td valign="top">Mengingat</td>
							<td valign="top">:</td>							
							<td><textarea name="isi_atas" cols="30" rows="4" value="{!! $isi->isi_atas !!}" class="form-control">{!! $isi->isi_atas !!}</textarea></td>							
							</tr>
							<tr>
							<td height="30" colspan="3" align="center"><b>MEMUTUSKAN</b></td>
							</tr>
							<tr>
							<td valign="top">Menetapkan</td>
							<td valign="top">:</td>
							<td>
							@foreach ($pengajuan as $pengajuan)
								<table width=50% >
									<tr>
									<td width=15%>Nama</td>
									<td width=3% >:</td>
									<td width=30%>{{ $pengajuan->fullname }}</td>
									</tr>
									<tr>
									<td>Nik</td>
									<td>:</td>
									<td>{{ $pengajuan->employee_id }}</td>
									</tr>
									<tr>
									<td>Divisi</td>
									<td>:</td>
									<td>{{ $pengajuan->department_name }}</td>
									</tr>
									<tr>
									<td>Jabatan</td>
									<td>:</td>
									<td>{{ $pengajuan->job_title_name }}</td>
									</tr>
									<tr>
									<td>Grade Title</td>
									<td>:</td>
									<td>{{ $pengajuan->level_title_name }}</td>
									</tr>
									<tr>
									<td>Lokasi</td>
									<td>:</td>
									<td>{{ $pengajuan->region_city }}</td>
									</tr>
									<tr>
									<td>Grade</td>
									<td>:</td>
									<td>{{ $pengajuan->Kelas_lama }}</td>
									</tr>
									<tr>
									<td>Level</td>
									<td>:</td>
									<td>{{ $pengajuan->Level_lama }}</td>
									</tr>
									 <tr>
									<td>Tanggal Masuk Kerja</td>
									<td>:</td>
									<td>{{ tgl_indo($pengajuan->date_of_work ) }}</td>
									</tr>
								</table>							
							</td>
							</tr>							
							<tr>							
							<td colspan="3"><textarea name="isi_tengah" cols="30" rows="4" value="{!! $isi->isi_tengah !!}" class="form-control">{!! $isi->isi_tengah !!}</textarea></td>`													
							</tr>
							</table>
							<table width=100%>
							<tr>
							<td valign="top" rowspan="2" width=20%>Dengan Catatan</td>
							<td valign="top" width=3% >:</td>
							<td valign="top" width=3% >1.</td>
							<td width=70%><textarea name="isi_bawah" cols="30" rows="4" value="{!! $isi->isi_bawah !!}" class="form-control">{!! $isi->isi_bawah !!}</textarea></td>
							</tr>
							<tr>
							<td></td>
							<td valign="top">2.</td>
							<td><textarea name="isi_footer" cols="30" rows="4" value="{!! $isi->isi_footer !!}" class="form-control">{!! $isi->isi_footer !!}</textarea></td>
							</tr>
							</table><br>
							<table width=30%>
							<tr>
							<td width=30% height="1">Ditetapkan</td>
							<td width=5%  height="1">:</td>
							<td width=50% height="1"><input type="text" name="footer" class="form-control" value="{!! $isi->footer !!}" placeholder="" ></td>
							</tr>
							<tr>
							<td colspan="3" height="2"><hr align="left" style="width:100%" ></hr></td>
							</tr>
							<tr>
							<td height="2">Pada Tanggal</td>
							<td height="2">:</td>
							<td height="2">{{ tgl_indo(date('Y-m-d')) }}</td>
							</tr>
							</table><br>
						@endforeach						
						</div>
                    </div>					
                    <div class="card-footer">
                    <table width="50%">
						<tr>
						<td align="left" width="25%"><b>Dibuat Oleh</b></td>
						<td align="left" width="25%"><b>Diketahui Oleh</b></td>						
						</tr> 
						<tr>
						<td height="50"></td>
						<td height="50"></td>					
						</tr> 
						<tr>
						<td align="left"><b><u>Aldo Omar</u></b></td>
						<td align="left"><b><u>Adhi S. Lukman</u></b></td>
						</tr> 
						<tr>
						<td align="left">HC Sr. Manager</td>
						<td align="left">HC Director</td>
						</tr> 
					</table><br><br>  					
                    </div>
					<table>
					<tr>
					<td colspan="4">Tembusan</td>
					</tr>
					<tr>
					<td>1.</td>
					<td><input type="text" name="arsip_1" class="form-control" value="{!! $isi->arsip_1 !!}" placeholder="" ></td>
					<td>6.</td>
					<td><input type="text" name="arsip_6" class="form-control" value="{!! $isi->arsip_6 !!}" placeholder="" ></td>
					</tr>
					<tr>
					<td>2.</td>
					<td><input type="text" name="arsip_2" class="form-control" value="{!! $isi->arsip_2 !!}" placeholder="" ></td>
					<td>7.</td>
					<td><input type="text" name="arsip_7" class="form-control" value="{!! $isi->arsip_7 !!}" placeholder="" ></td>
					</tr>
					<tr>
					<td>3.</td>
					<td><input type="text" name="arsip_3" class="form-control" value="{!! $isi->arsip_3 !!}" placeholder="" ></td>
					<td>8.</td>
					<td><input type="text" name="arsip_8" class="form-control" value="{!! $isi->arsip_8 !!}" placeholder="" ></td>
					</tr>
					<tr>
					<td>4.</td>
					<td><input type="text" name="arsip_4" class="form-control" value="{!! $isi->arsip_4 !!}" placeholder="" ></td>	
					<td>9.</td>
					<td><input type="text" name="arsip_9" class="form-control" value="{!! $isi->arsip_9 !!}" placeholder="" ></td>
					</tr>
					<tr>
					<td>5.</td>
					<td><input type="text" name="arsip_4" class="form-control" value="{!! $isi->arsip_4 !!}" placeholder="" ></td>	
					<td>10.</td>
					<td><input type="text" name="arsip_10" class="form-control" value="{!! $isi->arsip_10 !!}" placeholder="" ></td>
					</tr>
					</table>
					<div class="form-group">
					<button type="submit" class="btn btn-primary btn-md pull-right"><i class="fas fa-save"></i>Update<button>
					</div>
                </div>			  
			</div>
		</div>
    </div>
</form>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            History
                        </h4>
                    </div>
                    <div class="card-body">
                        <ol>
                            <h6>Created By</h6>
                           <li><i class="fas fa-check-circle text-success"></i> <b>{{ $pengajuan->fullname }}</b> {{ date('d-m-Y H:i:s', strtotime($isi->tgl_insert)) }}</li>
                        </ol>
                    </div>
                </div>
</div>
</body>
@endsection


@section('script')
<script>
	
	$('.datepicker').datetimepicker({
    format: 'DD-MM-YYYY'
	});
	
	$('.Gapok_baru').mask('000.000.000', {reverse: true});
	
	$(function()
    {
      $('[id="hal1"]').change(function()
      {
        if ($(this).is(':checked')) {
           // Do something...
           //alert('You can rock now...');
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  $('[id="hal4"]').change(function()
      {
        if ($(this).is(':checked')) {
           // Do something...
           //alert('You can rock now...');
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  $('[id="hal5"]').change(function()
      {
        if ($(this).is(':checked')) {
		   $("#formhpk").show();
		   $("#kontrak").show();
        }
		else
		{
			$("#formhpk").hide();
			$("#kontrak").hide();
		};

      });
	  $('[id="hal7"]').change(function()
      {
      if ($(this).is(':checked')) {
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  	  $('[id="hal8"]').change(function()
      {
        if ($(this).is(':checked')) {
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
    });
	
	
	$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#Departement_baru').on('change', function () {
        $.ajax({
            url: '{{ route('pengajuan.fpk.get_dept_list') }}',
            method: 'POST',
            data: {id: $(this).val()},
            success: function (response) {
                $('#Jabatan_baru').empty();

                $.each(response, function (id , name) {
                    $('#Jabatan_baru').append('<option value="' +id+ '">' +id+ '-'+ name +'</option>');
                })
            }
        })
    });
	});
	
	
	$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#Jabatan_baru').on('change', function () {
        $.ajax({
            url: '{{ route('pengajuan.fpk.get_atasan_list') }}',
            method: 'POST',
            data: {id: $(this).val()},
            success: function (response) {
                $('#Atasan_baru').empty();

                $.each(response, function (id, name, level) {
                    $('#Atasan_baru').append('<option value="'+id+'">'+name+'</option>');
                })
            }
        })
    });
	});
	


    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

</script>
@endsection