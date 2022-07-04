@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengajuan FPK</h4>
            {{ Breadcrumbs::render('pengajuan-fpk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Form Pembaharuan Karyawan (FPK)</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.fpk.submit') }}" method="POST" id="pengajuan_fpk" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										@foreach ($Approval as $Next)
										<input type="hidden" name="Approval" value="{{ $Next->id }}">
										<input type="hidden" name="LevelId" value="{{ $Next->grade_title_id }}">
										@endforeach
                                        <label for="">Nama Karyawan <span class="required-label">*</span></label>
                                        <select name="NameEmployee" class="form-control selectpicker" required>
                                            <option></option>
                                            @foreach ($employee as $item)
                                                <option value="{{ $item->id }}">{{ $item->registration_number.'-'.$item->fullname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">			
                                        <label for="">Tanggal Lahir</label>                                      
                                                <input type="text" class="form-control" name="tgl_lahir" placeholder="" readonly="readonly'">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pendidikan</label>
                                         <input type="text" class="form-control" name="pendidikan" placeholder="" readonly="readonly'">
                                    </div>
									<div class="form-group">
                                        <input type="radio" name="flag_mgr" value="1" > Manager
										<input type="radio" name="flag_mgr" value="0" > Non-Manager
                                    </div> 
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Masuk</label>                                     
                                                <input type="text" class="form-control" name="tgl_masuk" placeholder="" readonly="readonly'">
                                    </div>
                                    <div class="form-group">
                                         <label for="">Agama</label>                                     
                                                <input type="text" class="form-control" name="agama" placeholder="" readonly="readonly'">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lokasi Kerja</label>
										<input type="text" class="form-control" name="Lokasi_lama" placeholder="" readonly="readonly">                          
                                    </div>    
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Jenis Pembaharuan</label>
										<div class="row">
                                            <div class="col-md-6">
                                                <input type="checkbox" id="hal1" name="promosi" value="1">	Promosi<br>
                                                <input type="checkbox" id="hal2" name="perubahan_job" value="1"> Perubahan Job Title<br> 
												<input type="checkbox" id="hal3" name="penyesuaian_comben" value="1"> Penyesuaian Comben<br>
												<input type="checkbox" id="hal8" name="perubahan_status" value="1"> Perubahan Status
                                            </div>
                                            <div class="col-md-6">
                                                <input type="checkbox" id="hal4" name="demosi" value="1"> Demosi<br>
                                                <input type="checkbox" id="hal5" name="perpanjangan_kontrak" value="1"> Perpanjangan Kontrak Kerja<br> 
                                                <div class="form-group" id="kontrak" name="kontrak" style="display:none">
												<select name="kontrak_ke" class="form-control selectpicker">
													<option selected disabled>Kontrak Ke</option>
													@foreach ($KontrakOptions as $item)
														<option value="{{ $item }}">{{ strtoupper($item) }}</option>
													@endforeach
												</select>
												<label for="">Masa Kontrak<span class="required-label">*</span></label>
												 <select name="note_kontrak" class="form-control selectpicker">
													<option></option>
													@foreach ($masa_kontrak as $item)
														<option value="{{ $item }}">{{ $item }} Bulan</option>
													@endforeach
												 </select>
												 <!--
												<small class="text-muted">Masa kontrak silahkan isi : Cth. 6 bulan atau periode 01-01-2020 s/d 01-06-2020</small>
												-->
												</div>
												<input type="checkbox" id="hal6" name="habis_kontrak" value="1"> Habis Kontrak<br>
												<input type="checkbox" id="hal7" name="mutasi" value="1"> Mutasi
                                            </div>
										</div>
                                    </div>              
								</div> <br> 
							
								<div class="col-md-12">
								<div class="form-group" id="formhpk" name="formhpk" style="display:none">
								        <label for="">HASIL PENILAIAN KARYAWAN</label>
										<div class="row">
										<label for="">A. KEMAMPUAN</label>
										<table class="table table-bordered" >
										<tr>
											<td colspan="2" >Faktor - Faktor Yang Dinilai:</td>
											<td>BS</td>
											<td>B</td>
											<td>C</td>
											<td>K</td>
											<td>KS</td>
										</tr>
											<td>1.</td>
											<td>Pengetahuan dan Penguasaan terhadap pekerjaan</td>
											<td><input type="radio" name="A1" value="5"></td>
											<td><input type="radio" name="A1" value="4"></td>
											<td><input type="radio" name="A1" value="3"></td>
											<td><input type="radio" name="A1" value="2"></td>
											<td><input type="radio" name="A1" value="1"></td>
										<tr>
											<td>2.</td>
											<td>Ketekunan menghadapi pekerjaan</td>
											<td><input type="radio" name="A2" value="5"></td>
											<td><input type="radio" name="A2" value="4"></td>
											<td><input type="radio" name="A2" value="3"></td>
											<td><input type="radio" name="A2" value="2"></td>
											<td><input type="radio" name="A2" value="1"></td>
										</tr>
											<td>3.</td>
											<td>Mutu Pekerjaan</td>
											<td><input type="radio" name="A3" value="5"></td>
											<td><input type="radio" name="A3" value="4"></td>
											<td><input type="radio" name="A3" value="3"></td>
											<td><input type="radio" name="A3" value="2"></td>
											<td><input type="radio" name="A3" value="1"></td>
										<tr>
											<td>4.</td>
											<td>Inisiatif dan kreativitas dalam menjalankan tugas</td>
											<td><input type="radio" name="A4" value="5"></td>
											<td><input type="radio" name="A4" value="4"></td>
											<td><input type="radio" name="A4" value="3"></td>
											<td><input type="radio" name="A4" value="2"></td>
											<td><input type="radio" name="A4" value="1"></td>
										</tr>
										<tr>
											<td>5.</td>
											<td>Kepemimpinan</td>
											<td><input type="radio" name="A5" value="5"></td>
											<td><input type="radio" name="A5" value="4"></td>
											<td><input type="radio" name="A5" value="3"></td>
											<td><input type="radio" name="A5" value="2"></td>
											<td><input type="radio" name="A5" value="1"></td>
										</tr>
										</table>
										<label for="">B. SIKAP/ATTITUDE</label>
										<table class="table table-bordered" >
										<tr>
											<td colspan="2" >Faktor - Faktor Yang Dinilai:</td>
											<td>BS</td>
											<td>B</td>
											<td>C</td>
											<td>K</td>
											<td>KS</td>
										</tr>
											<td>1.</td>
											<td>Kerjasama dengan teman sekerja</td>
											<td><input type="radio" name="B1" value="5"></td>
											<td><input type="radio" name="B1" value="4"></td>
											<td><input type="radio" name="B1" value="3"></td>
											<td><input type="radio" name="B1" value="2"></td>
											<td><input type="radio" name="B1" value="1"></td>
										<tr>
											<td>2.</td>
											<td>Mengindahkan Instruksi Atasan</td>
											<td><input type="radio" name="B2" value="5"></td>
											<td><input type="radio" name="B2" value="4"></td>
											<td><input type="radio" name="B2" value="3"></td>
											<td><input type="radio" name="B2" value="2"></td>
											<td><input type="radio" name="B2" value="1"></td>
										</tr>
											<td>3.</td>
											<td>Interaksi dengan rekan kerja & lingkungan perusahaan</td>
											<td><input type="radio" name="B3" value="5"></td>
											<td><input type="radio" name="B3" value="4"></td>
											<td><input type="radio" name="B3" value="3"></td>
											<td><input type="radio" name="B3" value="2"></td>
											<td><input type="radio" name="B3" value="1"></td>
										<tr>
											<td>4.</td>
											<td>Absensi dan hadir secara tepat waktu dan teratur</td>
											<td><input type="radio" name="B4" value="5"></td>
											<td><input type="radio" name="B4" value="4"></td>
											<td><input type="radio" name="B4" value="3"></td>
											<td><input type="radio" name="B4" value="2"></td>
											<td><input type="radio" name="B4" value="1"></td>
										</tr>
										<tr>
											<td>5.</td>
											<td>Kedisiplinan dan menghargai waktu kerja</td>
											<td><input type="radio" name="B5" value="5"></td>
											<td><input type="radio" name="B5" value="4"></td>
											<td><input type="radio" name="B5" value="3"></td>
											<td><input type="radio" name="B5" value="2"></td>
											<td><input type="radio" name="B5" value="1"></td>
										</tr>
										</table><br>
										<div>Keterangan: <br> BS= Baik Sekali &nbsp; B= Baik &nbsp; C= Cukup &nbsp; K= Kurang &nbsp; KS= Kurang Sekali</div>										
									</div>
										<br>										
										<div>
                                        <label for="">Kekuatan</label>
                                        <textarea name="kelebihan" cols="30" rows="2" class="form-control"></textarea>
										</div>
										<br>
										<div>
                                        <label for="">Kelemahan</label>
                                        <textarea name="kekurangan" cols="30" rows="2" class="form-control"></textarea>
										</div>
								</div>
								</div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Data Perubahan<span class="required-label">*</span></label>
                                        <div class="row">
											<table class="table table-borderless">
												<tr>
													<td align="center" width="20%">PERUBAHAN</td>
													<td align="center" width="40%">LAMA</td>
													<td align="center" width="40%">BARU</td>
												</tr>
												<tr>
													<td>Departement</td>										
													<td>
													<input type="hidden" class="form-control" name="Departementx" placeholder="" readonly="readonly">
													<input type="text" class="form-control" name="Departement_lama" placeholder="" readonly="readonly"></td>
													<td>
													<select id="Departement_baru" name="Departement_baru" class="form-control selectpicker" >
													<option></option>
													@foreach ($departments as $item)
														<option value="{{ $item->department_code }}">{{ $item->department_code.'-'.$item->department_name }}</option>
													@endforeach
													</select>
													</td>
												</tr>
												<tr>												
													<td>Jabatan</td>
													<td>
													<input type="hidden" class="form-control" name="Jabatanx" placeholder="" readonly="readonly">
													<input type="text" class="form-control" name="Jabatan_lama" placeholder="" readonly="readonly"></td>
													<td>
													<select name="Jabatan_baru" id="Jabatan_baru" class="form-control selectpicker" >
													<option></option>
													</select>
													</td>
												</tr>
												<tr>
													<td>Grade / Golongan</td>
													<td><input type="text" class="form-control" name="Kelas_lama" placeholder="" readonly="readonly"></td>
													<td>
													<select name="Kelas_baru" class="form-control selectpicker" >
													<option></option>
													@foreach ($gradeOptions as $item)
														<option value="{{ $item }}">{{ strtoupper($item) }}</option>
													@endforeach
													</select>
												</td>
												</tr>
												<tr>
													<td>Kelas / Level</td>
													<td><input type="text" class="form-control" name="level_lama" placeholder="" readonly="readonly"></td>
													<td><br>
													<select name="Level" class="form-control selectpicker" required disabled>
														<option></option>
                                        			</select>
													<small class="text-muted"><font color="red">Untuk Promosi, level yang diisi hanya satu tingkat diatas level sebelumnya</font></small>
                                        			</td>
												</tr>									
												<tr>
													<td>Lokasi Kerja</td>
													<td><input type="text" class="form-control" name="Lokasi_lama" placeholder="" readonly="readonly"></td>
													<td>                                        
													<select name="Lokasi_baru" class="form-control selectpicker" required >
													<option></option>
													@foreach ($company_regions as $item)
														<option value="{{ $item->id }}">{{ $item->region_city }}</option>
													@endforeach
													</select></td>
												</tr>
												<tr>
													<td>Atasan</td>
													<td>
													<input type="hidden" class="form-control" name="Atasan_lamax" value="{{ $direct_superior->id }}" placeholder="" readonly="readonly">
													<input type="text" class="form-control" name="Atasan_lama" value="" placeholder="" readonly="readonly"></td>
													<td>                                        
													<select name="Atasan_baru" id="Atasan_baru" class="form-control selectpicker">
													<option></option>
													</select>
												</td>
												</tr>
												<tr>
													<td>Status Karyawan</td>
													<td><input type="text" class="form-control" name="Status_lama" placeholder="" readonly="readonly"></td>
													<td><select name="Status_baru" class="form-control selectpicker" >
													<option></option>
													@foreach ($HalOptions as $item)
														<option value="{{ $item }}">{{ $item }}</option>
													@endforeach
													</select></td>
												</tr>
												<tr>
													<td>Gaji Pokok</td>
													<td><input type="text" class="form-control" name="Gapok_lama" placeholder="" readonly="readonly"></td>
													<td>
													<input type="text" class="form-control Gapok_baru" name="Gapok_baru" placeholder="Tidak perlu diisi" disabled >												
													</td>
												</tr>
												<!--
												<tr>
													<td>Tunjangan Transport</td>
													<td><input type="text" class="form-control" name="Tuport_lama" placeholder="" readonly="readonly"></td>
													<td><input type="text" class="form-control" name="Tuport_baru" placeholder="Isi Disini" ></td>
												</tr>
												-->
												<tr>
													<td>Tunjangan Makan</td>
													<td><input type="text" class="form-control" name="Tukan_lama" placeholder="" disabled></td>
													<td><select name="Tukan_baru" class="form-control selectpicker" >
													<option></option>
													@foreach ($mealOptions as $item)
														<option value="{{ $item }}">{{ $item }}</option>
													@endforeach
													</select></td>
												</tr>
											</table>
										</div>  
										
										<div class="form-group">
                                        <label for="">Persiapan Peralatan dan Fasilitas Kerja</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Mobil"> Mobil<br>
                                                <input type="checkbox" name="facilities[]" value="Meja"> Meja                                              
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="Laptop"> Laptop<br>
                                                <input type="checkbox" name="facilities[]" value="PC"> PC                                               
                                            </div>
											<div class="col-md-3">
                                                <input type="checkbox" name="facilities[]" value="HP"> Handphone<br>
                                                <input type="checkbox" name="facilities[]" value="Kursi"> Kursi             
                                            </div>
											</div>
                                        </div><br>

										<div class="form-group">
                                        <label for="">Tanggal Efektif <span class="required-label">*</span></label>
                                        <input type="text" class="form-control datepicker" name="Effdate" required>
										</div>
										
                                                                              
                                    </div>
                                    <div class="form-group">
                                        <label for="">Catatan</label>
                                        <textarea name="Notes" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Diajukan Oleh(Atasan Langsung)</label>
                                        <select name="DirectSuperior" class="form-control selectpicker" required>
                                            <option></option>
                                            <option value="{{ $direct_superior->id }}">
                                                {{ $direct_superior->fullname.'-'.$direct_superior->level_title->level_title_name }}
                                            </option>
                                        </select>
                                    </div>
								<!--
                                    <div class="form-group">
                                        <label for="">Atasan Tidak Langsung <span class="required-label">*</span></label>
                                        <input type="text" name="InDirectSuperior" class="form-control" disabled>
                                    </div>
								-->
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
    </div>
</div>
@endsection

@section('script')
<script>

	$('.Gapok_baru').mask('000.000.000', {reverse: true});
	
	var Days = new Date(); 
	Days.setDate(Days.getDate()+14);
	$('.datepicker').datetimepicker({
    format: 'DD-MM-YYYY',
	minDate: Days
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
	  $('[id="hal6"]').change(function()
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
	
	
    var dt = $('#training-submissions-table').dataTable({
        // responsive: true,
    }).api();



    $('select[name=NameEmployee]').on('change', function(){
        var id = $(this).val();

        $.ajax({
            url: '{{ route('pengajuan.fpk.get_data_employee') }}',
            type: 'GET',
            dataType:'JSON',
            data: {
                'id':id,
            },
            success: function(resp){
                $('input[name=tgl_lahir]').val(resp.tgl_lahir);
				$('input[name=agama]').val(resp.value1);
				$('input[name=tgl_masuk]').val(resp.tgl_masuk);
				$('input[name=pendidikan]').val(resp.pendidikan);
				$('input[name=Jabatan_lama]').val(resp.jabatan);
				$('input[name=Jabatanx]').val(resp.jabatanx);
				$('input[name=Status_lama]').val(resp.stat_kary);
				$('input[name=Kelas_lama]').val(resp.kelas);
				$('input[name=Departement_lama]').val(resp.departement);
				$('input[name=Departementx]').val(resp.departementx);
				$('input[name=Lokasi_lama]').val(resp.lokasi);
				$('input[name=Tukan_lama]').val(resp.tunkan);
				$('input[name=Gapok_lama]').val(resp.Gapok_lama);
				$('input[name=level_lama]').val(resp.level_lama);
            },
            error: function(err){
                showNotification('error', err.error.toString);
            }
        })
    });
	
	
	$('select[name=NameEmployee]').on('change', function(){
        var id = $(this).val();

        $.ajax({
            url: '{{ route('pengajuan.fpk.get_superior') }}',
            type: 'GET',
            dataType:'JSON',
            data: {
                'id':id,
            },
            success: function(resp){
                $('input[name=Atasan_lama]').val(resp.value);
            },
            error: function(err){
                showNotification('error', err.error.toString);
            }
        })
    });
		
	
	$('select[name=DirectSuperior]').on('change', function(){
        var id = $(this).val();

        $.ajax({
            url: '{{ route('pengajuan.fpk.get_indirect_superior') }}',
            type: 'GET',
            dataType:'JSON',
            data: {
                'id':id,
            },
            success: function(resp){
                $('input[name=InDirectSuperior]').val(resp.value);
            },
            error: function(err){
                showNotification('error', err.error.toString);
            }
        })
    });
	
	

    $('select[name=Kelas_baru]').on('change', function(e){
        var grade = $(this).val();
        changeLevelOptions(grade);
    });

    function changeLevelOptions(grade){
        var select_level = $('select[name=Level]');
        select_level.select2().empty();

        if (grade === 'I') {
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 1,
                        text: '1'
                    },
                ]
            });
        }else if(grade == 'II'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 2,
                        text: '2'
                    },
                ]
            });
        }else if(grade == 'III'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
					{
                        id: 3,
                        text: '3'
                    },
                    {
                        id: 4,
                        text: '4'
                    },
					{
                        id: 5,
                        text: '5'
                    },
					{
                        id: 6,
                        text: '6'
                    },
					 {
                        id: 7,
                        text: '7'
                    },
					{
                        id: 8,
                        text: '8'
                    },
					{
                        id: 9,
                        text: '9'
                    },
                ]
            });
        }else if(grade == 'IV'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                   {
                        id: 10,
                        text: '10'
                    },
					{
                        id: 11,
                        text: '11'
                    },
					{
                        id: 12,
                        text: '12'
                    },
                ]
            });
        }else if(grade == 'V'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 13,
                        text: '13'
                    },
					{
                        id: 14,
                        text: '14'
                    },
					{
                        id: 15,
                        text: '15'
                    },				
                ]
            });
        }else if(grade == 'VI'){
            select_level.select2({
                theme: 'bootstrap',
                data: [
                    {
                        id: 16,
                        text: '16'
                    },
					{
                        id: 17,
                        text: '17'
                    },
					{
                        id: 18,
                        text: '18'
                    },		
					
                ]
            });
        }
        select_level.trigger('change');
        select_level.attr('disabled', false);
    }

    $('input[name="QtyBoth"]').on('input', function(){
        var ini = $(this).val().length;
        if (ini != 0) {
            $('input[name="QtyMale"]').attr('disabled', true);
            $('input[name="QtyFemale"]').attr('disabled', true);
        }else{
            $('input[name="QtyMale"]').attr('disabled', false);
            $('input[name="QtyFemale"]').attr('disabled', false);
        }
    });
</script>
@endsection