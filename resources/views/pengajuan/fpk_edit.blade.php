@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Pengajuan FPK</h4>
            {{ Breadcrumbs::render('pengajuan-fpk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Form Pembaharuan Karyawan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.fpk.submit_edit') }}" method="POST" id="pengajuan_fpk" enctype="multipart/form-data">
                            @csrf
							<input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">	
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nama Karyawan <span class="required-label">*</span></label>
  
                                            @foreach ($employeemaster as $item)
                                              <input type="hidden" class="form-control" name="NamaEmployee" value="{{ $item->id }}">
											  <input type="text" class="form-control" value="{{ $item->employee_id.'-'.$item->fullname }}" placeholder="" readonly="readonly'">
                                            @endforeach
							
                                    </div>
                                    <div class="form-group">			
                                        <label for="">Tanggal Lahir</label>    
											 @foreach ($employeemaster as $item)
                                                <input type="text" class="form-control" value="{{  date('d-F-Y',strtotime($item->date_of_birth)) }}" placeholder="" readonly="readonly'">
											 @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Pendidikan</label>
												@foreach ($employeemaster as $item)
                                                <input type="text" class="form-control" value="{{ $item->last_education.'-'.$item->education_focus }}" placeholder="" readonly="readonly'">
											 @endforeach                                         
                                    </div>
									<div class="form-group">
										<input type="radio" name="flag_mgr" value="1" {{ $pengajuan->flag_mgr == '1' ? 'checked' : '' }} >Manager
										<input type="radio" name="flag_mgr" value="0" {{ $pengajuan->flag_mgr == '0' ? 'checked' : '' }} >Non-Manager
                                    </div> 
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Masuk</label>                                     
                                             @foreach ($employeemaster as $item)
                                                <input type="text" class="form-control" value="{{  date('d-F-Y',strtotime($item->date_of_work)) }}" placeholder="" readonly="readonly'">
											 @endforeach
                                    </div>
                                    <div class="form-group">
                                         <label for="">Agama </label>                                     
                                                @foreach ($employeemaster as $item)
                                                <input type="text" class="form-control" value="{{ $item->religion }}" placeholder="" readonly="readonly'">
												@endforeach
                                    </div>
                                    <div class="form-group">
                                        <label for="">Lokasi Kerja</label>
											<input type="text" class="form-control" name="homebase" value="{{ $pengajuan->Lokasi_lama }}" placeholder="" readonly="readonly'">                          
                                    </div>  
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">					
                                        <label for="">Jenis Pembaharuan<span class="required-label">*</span></label>
										<div class="row">																	
											<div class="col-md-6">
											@foreach ($perihal as $item)
												<input type="checkbox" id="hal1" name="promosi" value="1"{{ $item->promosi == '1' ? 'checked' : '' }} >Promosi<br>											
                                                <input type="checkbox" id="hal2" name="perubahan_job" value="1"{{ $item->perubahan_job == '1' ? 'checked' : '' }} >Perubahan Job Title<br>
												<input type="checkbox" id="hal3" name="penyesuaian_comben" value="1" {{ $item->penyesuaian_comben == '1' ? 'checked' : '' }} >Penyesuaian Comben<br>
												<input type="checkbox" id="hal8" name="perubahan_status" value="1" {{ $item->perubahan_status == '1' ? 'checked' : '' }} >Perubahan Status 
											@endforeach
                                            </div>
                                            <div class="col-md-6">
											@foreach ($perihal as $item)
                                                <input type="checkbox" id="hal4" name="demosi" value="1"{{ $item->demosi == '1' ? 'checked' : '' }} >Demosi<br>
                                                <input type="checkbox" id="hal5" name="perpanjangan_kontrak" value="1" {{ $item->perpanjangan_kontrak == '1' ? 'checked' : '' }} >Perpanjangan Kontrak Kerja<br> 
                                            @endforeach
                                                @if($pengajuan->flag_kontrak == 0)
												<div class="form-group" id="kontrak" name="kontrak" style="display:none">
												@else
												<div class="form-group" id="kontrak" name="kontrak">
												@endif
													<select name="kontrak_ke" class="form-control selectpicker" >
														<option></option>
														@foreach ($KontrakOptions as $item)
															<option value="{{ $item }}"{{ $pengajuan->kontrak_ke == $item ? 'selected':'' }}> {{ $item }}</option>
														@endforeach
													</select>
												<label for="">Note Kontrak</label>
												<input type="text" class="form-control" value="{{ $pengajuan->note_kontrak }}" name="note_kontrak" placeholder="">
												<br>
												<small class="text-muted">Note kontrak silahkan isi : Cth. 6 bulan atau periode 01-01-2020 s/d 01-06-2020</small>
												</div>
                                            @foreach ($perihal as $item)
												<input type="checkbox" id="hal6" name="habis_kontrak" value="1" {{ $item->habis_kontrak == '1' ? 'checked' : '' }} >Habis Kontrak<br>
												<input type="checkbox" id="hal7" name="mutasi" value="1" {{ $item->mutasi == '1' ? 'checked' : '' }} >Mutasi
											@endforeach
                                            </div>
										</div>
                                    </div>              
								</div> <br> 
								

								<div class="col-md-12">
								@if($pengajuan->flag_hpk == 0)							
								<div class="form-group" id="formhpk" name="formhpk" style="display:none">
								@else
								<div class="form-group" id="formhpk" name="formhpk">
								@endif
								        <label for="">HASIL PENILAIAN KARYAWAN<span class="required-label">*</span></label>
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
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="A1" value="5" {{ $nilai->A1 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A1" value="4" {{ $nilai->A1 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A1" value="3" {{ $nilai->A1 == '3' ? 'checked' : '' }} ></td>									
											<td><input type="radio" name="A1" value="2" {{ $nilai->A1 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A1" value="1" {{ $nilai->A1 == '1' ? 'checked' : '' }} ></td>	
											@endforeach												
										<tr>
											<td>2.</td>
											<td>Ketekunan menghadapi pekerjaan</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="A2" value="5" {{ $nilai->A2 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A2" value="4" {{ $nilai->A2 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A2" value="3" {{ $nilai->A2 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A2" value="2" {{ $nilai->A2 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A2" value="1" {{ $nilai->A2 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										</tr>
											<td>3.</td>
											<td>Mutu Pekerjaan</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="A3" value="5" {{ $nilai->A3 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A3" value="4" {{ $nilai->A3 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A3" value="3" {{ $nilai->A3 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A3" value="2" {{ $nilai->A3 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A3" value="1" {{ $nilai->A3 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										<tr>
											<td>4.</td>											
											<td>Inisiatif dan kreativitas dalam menjalankan tugas</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="A4" value="5" {{ $nilai->A4 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A4" value="4" {{ $nilai->A4 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A4" value="3" {{ $nilai->A4 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A4" value="2" {{ $nilai->A4 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A4" value="1" {{ $nilai->A4 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										</tr>
										<tr>
											<td>5.</td>
											<td>Kepemimpinan</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="A5" value="5" {{ $nilai->A5 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A5" value="4" {{ $nilai->A5 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A5" value="3" {{ $nilai->A5 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A5" value="2" {{ $nilai->A5 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="A5" value="1" {{ $nilai->A5 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
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
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="B1" value="5" {{ $nilai->B1 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B1" value="4" {{ $nilai->B1 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B1" value="3" {{ $nilai->B1 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B1" value="2" {{ $nilai->B1 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B1" value="1" {{ $nilai->B1 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										<tr>
											<td>2.</td>
											<td>Mengindahkan Instruksi Atasan</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="B2" value="5" {{ $nilai->B2 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B2" value="4" {{ $nilai->B2 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B2" value="3" {{ $nilai->B2 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B2" value="2" {{ $nilai->B2 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B2" value="1" {{ $nilai->B2 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										</tr>
											<td>3.</td>
											<td>Interaksi dengan rekan kerja & lingkungan perusahaan</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="B3" value="5" {{ $nilai->B3 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B3" value="4" {{ $nilai->B3 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B3" value="3" {{ $nilai->B3 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B3" value="2" {{ $nilai->B3 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B3" value="1" {{ $nilai->B3 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										<tr>
											<td>4.</td>
											<td>Absensi dan hadir secara tepat waktu dan teratur</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="B4" value="5" {{ $nilai->B4 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B4" value="4" {{ $nilai->B4 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B4" value="3" {{ $nilai->B4 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B4" value="2" {{ $nilai->B4 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B4" value="1" {{ $nilai->B4 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										</tr>
										<tr>
											<td>5.</td>
											<td>Kedisiplinan dan menghargai waktu kerja</td>
											@foreach ($penilaian as $nilai)
											<td><input type="radio" name="B5" value="5" {{ $nilai->B5 == '5' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B5" value="4" {{ $nilai->B5 == '4' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B5" value="3" {{ $nilai->B5 == '3' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B5" value="2" {{ $nilai->B5 == '2' ? 'checked' : '' }} ></td>
											<td><input type="radio" name="B5" value="1" {{ $nilai->B5 == '1' ? 'checked' : '' }} ></td>
											@endforeach	
										</tr>
										</table><br>
										<div>Keterangan: <br> BS= Baik Sekali &nbsp; B= Baik &nbsp; C= Cukup &nbsp; K= Kurang &nbsp; KS= Kurang Sekali</div> <br>																		
									</div>
										<div class="form-group">
                                        <label for="">Kekuatan</label>
										@foreach ($penilaian as $nilai)
                                        <textarea name="kelebihan" cols="30" value="{{ $nilai->kelebihan }}" rows="2" class="form-control">{{ $nilai->kelebihan }}</textarea>
										@endforeach	
										</div>
										<br>
										<div class="form-group">
                                        <label for="">Kelemahan</label>
										@foreach ($penilaian as $nilai)
                                        <textarea name="kekurangan" cols="30" value="{{ $nilai->kekurangan }}" rows="2" class="form-control">{{ $nilai->kekurangan }}</textarea>
										@endforeach	
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
													@foreach ($employeemaster as $item)
													<input type="hidden" class="form-control" name="Departementx" value="{{ $item->department_code }}" readonly="readonly">
													<input type="text" class="form-control" name="Departement_lama" value="{{ $item->department_code.'-'.$item->department_name }}" placeholder="" readonly="readonly"></td>
													@endforeach
													<td>
													<select name="Departement_baru" id="Departement_baru" class="form-control selectpicker" required>
													<option></option>
													@foreach ($departments as $item)
														<option value="{{ $item->department_code }}" {{ $pengajuan->Dept_baru == $item->department_code ? 'selected':'' }}> {{ $item->department_code.'-'.$item->department_name }}</option>
													@endforeach
													</select>
													</td>
												</tr>
												<tr>
													<td>Jabatan</td>
													<td>
													@foreach ($employeemaster as $item)
													<input type="hidden" class="form-control" name="Jabatanx" value="{{ $item->job_title_code }}" readonly="readonly">													
													<input type="text" class="form-control" name="Jabatan_lama" value="{{ $item->job_title_code.'-'.$item->job_title_name }}" readonly="readonly"></td>
													@endforeach
													<td>
													<select name="Jabatan_baru" id="Jabatan_baru" class="form-control selectpicker" required>
													<option></option>
													@foreach ($listjabatan as $item)
														<option value="{{ $item->id }}"{{ $pengajuan->Jab_baru == $item->id ? 'selected':'' }}>{{ $item->id.'-'.$item->name }}</option>
													@endforeach
													</select>
													</td>
												</tr>
												<tr>
													<td>Grade / Golongan</td>
													<td><input type="text" class="form-control" name="Kelas_lama" value="{{ $pengajuan->Kelas_lama }}" readonly="readonly"></td>
													<td>
													<select name="Kelas_baru" class="form-control selectpicker" required>
													<option></option>
													@foreach ($gradeOptions as $item)
														<option value="{{ $item }}"{{ $pengajuan->Kelas_baru == $item ? 'selected':'' }}> {{ $item }}</option>
													@endforeach
													</select>
												</td>
												</tr>
												<tr>
													<td>Kelas / Level</td>
													<td><input type="text" class="form-control" name="Level_lama" value="{{ $pengajuan->Level_lama }}" readonly="readonly"></td>
													<td><select name="Level" class="form-control selectpicker" id="level" required disabled>
														<option></option>
														</select>
														<!-- <br><input type="text" class="form-control" name="Level" value="{{ $pengajuan->Level }}" > -->
													<small class="text-muted"><font color="red">Untuk Promosi, level yang diisi hanya satu tingkat diatas level sebelumnya</font></small></td>
												</tr>												
												<tr>
													<td>Lokasi Kerja</td>
													<td><input type="text" class="form-control" name="Lokasi_lama" value="{{ $pengajuan->Lokasi_lama }}" readonly="readonly"></td>
													<td>                                        
													<select name="Lokasi_baru" class="form-control selectpicker" required>
													<option></option>
													@foreach ($company_regions as $item)
													<option value="{{ $item->id }}" {{ $pengajuan->Lokasi_baru == $item->id ? 'selected':'' }}>{{ $item->region_city }}</option>
													@endforeach
													</select></td>
												</tr>
												<tr>
													<td>Atasan</td>
													<td>
													<select name="Atasan_lama" class="form-control selectpicker" disabled>
													<option></option>
													@foreach ($employees as $item)
													<option  value="{{ $item->id }}" {{ $pengajuan->Atasan_lama == $item->id ? 'selected':'' }}> {{ $item->fullname }} </option>
													@endforeach
													</select>
													</td>
													<td>                                        
													<select name="Atasan_baru" id="Atasan_baru" class="form-control selectpicker">
													<option></option>
													@foreach ($listatasan as $item)
													<option value="{{ $item->id }}" {{ $pengajuan->Atasan_baru == $item->id ? 'selected':'' }}> {{ $item->name }}</option>
													@endforeach
													</select>
												</td>
												</tr>
												<tr>
													<td>Status Karyawan</td>
													<td><input type="text" class="form-control" name="Status_lama" value="{{ $pengajuan->Status_lama }}" placeholder="" readonly="readonly"></td>
													<td><select name="Status_baru" class="form-control selectpicker" >
													<option></option>
													@foreach ($HalOptions as $item)
														<option value="{{ $item }}" {{ $pengajuan->Status_baru == $item ? 'selected':'' }} >{{ $item }}</option>
													@endforeach
													</select></td>
												</tr>
												<tr>
													<td>Gaji Pokok</td>
													<td><input type="text" class="form-control" name="Gapok_lama" value="{{ $pengajuan->Gapok_lama }}" readonly="readonly"></td>
													<td>
													<input type="text" class="form-control" name="Gapok_baru" value="{{ $pengajuan->Gapok_baru }}" placeholder="Tidak perlu diisi" disabled>
													</td>
												</tr>
												<!--
												<tr>
													<td>Tunjangan Transport</td>
													<td><input type="text" class="form-control" name="Tunport_lama" value="{{ $pengajuan->Tuport_lama }}" readonly="readonly"></td>
													<td><input type="text" class="form-control" name="Tunport_baru" value="{{ $pengajuan->Tuport_baru }}" ></td>
												</tr>
												-->
												<tr>
													<td>Tunjangan Makan</td>
													<td><input type="text" class="form-control" name="Tukan_lama" value="{{ $pengajuan->Tukan_lama }}" placeholder="" disabled></td>
													<td>
													<select name="Tukan_baru" class="form-control selectpicker">
													<option></option>
													@foreach ($mealOptions as $item)
														<option value="{{ $item }}"{{ $pengajuan->Tukan_baru == $item ? 'selected':'' }}> {{ $item }}</option>
													@endforeach
													</select>
													</td>
												</tr>
											</table>
										</div>  
										
										<div class="form-group">
                                        <label for="">Persiapan Peralatan dan Fasilitas Kerja <span class="required-label">*</span></label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                @foreach ($facilities as $item)
                                                @if ($loop->index%2 == 0 && $loop->index != 0)
                                                    </div>
                                                    <div class="col-md-3">
                                                @endif
                                                <input type="checkbox" name="facilities[]" value="{{ $item->lookup_value }}" {{ $inserted_fac->contains('Description', $item->lookup_value) ? 'checked':'' }}>{{ $item->lookup_desc }}<br>
                                                @if ($loop->last)
                                                    </div>
                                                @endif
                                            @endforeach
                                            @php
                                                $unset_fac = $inserted_fac->whereNotIn('Description', $facilities->pluck('lookup_value'));
                                            @endphp
                                            @if ($unset_fac)
                                            <div class="col-md-3">
                                                @foreach ($unset_fac as $item)
                                                    @if ($item->Description)
                                                        <input type="checkbox" name="facilities[]" value="{{ $item->Description }}" checked>{{ $item->Description }}<br>
                                                    @endif
                                                @endforeach
                                            </div>
                                            @endif
											</div>
                                        </div><br>

										<div class="form-group">
                                        <label for="">Tanggal Efektif <span class="required-label">*</span></label>
                                        <input type="text" class="form-control datepicker" name="Effdate" required value="{{ date('d-m-Y',strtotime($pengajuan->Eff_date)) }}">
										</div>
										
                                                                              
                                    </div>
                                    <div class="form-group">
                                        <label for="">Catatan</label>
                                        <textarea name="Notes" cols="30" rows="4" value="{{ $pengajuan->Notes }}" class="form-control">{{ $pengajuan->Notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md pull-right"><i class="fas fa-save"></i>Update<button>
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
	


    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

</script>
@endsection