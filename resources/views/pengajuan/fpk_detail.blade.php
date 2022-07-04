@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Form Pembaharuan Karyawan</h4>
            {{ Breadcrumbs::render('pengajuan-fpk') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Deskripsi</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nomor FPK</dt>
                                    <dd>{{ $pengajuan->fpk_no ?? '-' }}</dd>
                                    <dt>NIK / Nama</dt>
                                    <dd>{{ $pengajuan->employee_id .'-'.  $pengajuan->fullname }}</dd>
                                    <dt>Tgl. Lahir</dt>
                                    <dd>{{ date('d-F-Y', strtotime($pengajuan->date_of_birth)) }}</dd>
                                    <dt>Tgl. Masuk</dt>
                                    <dd>{{ date('d-F-Y', strtotime($pengajuan->date_of_work ))}}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Lokasi Kerja</dt>
                                    <dd>{{ $pengajuan->Lokasi_lama }}</dd>
                                    <dt>Agama</dt>
                                    <dd>{{ $pengajuan->religion }}</dd>
                                    <dt>Pendidikan</dt>
                                    <dd>{{ $pengajuan->last_education .'-'. $pengajuan->education_focus }}</dd>                               
                                    <dt>Type</dt>
                                    <dd>
										@if ($pengajuan->flag_mgr == 1){{ "Manager" }} @endif
										@if ($pengajuan->flag_mgr == 0){{ "Non-Manager" }} @endif
                                    </dd>
                                    <dt>Catatan</dt>
                                    <dd>{{ $pengajuan->Notes}}</dd>
                                    <dt>Atasan Langsung</dt>
                                    <dd>{{ $pengajuan->nama_creator }} ({{ $pengajuan->registration_number }})</dd>
                                    <dt>Diajukan Oleh</dt>
                                    <dd>{{ $pengajuan->nama_creator }} ({{ $pengajuan->registration_number }})</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Jenis Pembaharuan</dt>
                                    <dd>
										@if ($pengajuan->promosi == 1){{ "Promosi"  }} <br> @endif
										@if ($pengajuan->demosi == 1){{ "Demosi"  }} <br> @endif
										@if ($pengajuan->mutasi == 1){{ "Mutasi"  }} <br> @endif
										@if ($pengajuan->perubahan_job == 1){{ "Perubahan Job Title"  }} <br> @endif
										@if ($pengajuan->perubahan_status == 1){{ "Perubahan Status"  }} <br> @endif
										@if ($pengajuan->penyesuaian_comben == 1){{ "Penyesuaian Comben"  }} <br> @endif
										@if ($pengajuan->perpanjangan_kontrak == 1){{ "Perpanjangan Kontrak Ke " }} {{ $pengajuan->kontrak_ke }}  <br>@endif
										@if ($pengajuan->habis_kontrak == 1){{ "Habis Kontrak"  }} <br> @endif
                                    </dd>
                                    @if ($pengajuan->flag_kontrak == 1)
									<dt>Masa Perpanjangan Kontrak</dt>
									<dd>{{ $pengajuan->note_kontrak }} Bulan</dd>
									@endif
                                </dl>
                            </div>
							<div class="col-md-6">
                                <dl>
                                    <dt>Data Perubahan</dt>
                                    <dd>
										<table border="1" >
											<tr>
												<td align="center" width="25%">PERUBAHAN</td>
												<td align="center" width="25%">LAMA</td>
												<td align="center" width="25%">BARU</td>
											</tr>
											<tr>
												<td>Departement / Divisi</td>
												<td>{{ $pengajuan->Dept_lama }} ({{ $pengajuan->department_name }})</td>
												<td>{{ $pengajuan->Dept_baru }} ({{ $pengajuan->dept_baru }})</td>
											</tr>
											<tr>
												<td>Jabatan</td>
												<td>{{ $pengajuan->Jab_lama }} ({{ $pengajuan->job_title_name }})</td>
												<td>{{ $pengajuan->Jab_baru }} ({{ $pengajuan->jab_baru }})</td>
											</tr>
											<tr>
												<td>Kelas / Golongan</td>
												<td>{{ $pengajuan->Kelas_lama }}</td>
												<td>{{ $pengajuan->Kelas_baru }}</td>
											</tr>
											<tr>
												<td>Grade / Level</td>
												<td>{{ $pengajuan->Level_lama ?? '-' }}</td>
												<td>{{ $pengajuan->Level ?? '-' }}</td>
											</tr>											
											<tr>
												<td>Lokasi Kerja</td>
												<td>{{ $pengajuan->Lokasi_lama }}</td>
												<td>{{ $pengajuan->region_city }}</td>
											</tr>
											<tr>
												<td>Atasan</td>
												<td>{{ $pengajuan->nama_creator }}</td>
												<td>{{ $pengajuan->nama_atasan_baru ?? '-'}}</td>
											</tr>
											<tr>
												<td>Status Karyawan</td>
												<td>{{ $pengajuan->Status_lama }}</td>
												<td>{{ $pengajuan->Status_baru ?? '-'}}</td>
											</tr>
											<tr>
												<td>Gaji Pokok</td>
												<td>{{ number_format($pengajuan->Gapok_lama, 2) ?? '-' }}</td>
												<td>{{ number_format($pengajuan->Gapok_baru, 2) ?? '-' }}</td>
											</tr>
										<!--
											<tr>
												<td>Tunjangan Transport</td>
												<td>{{ $pengajuan->Tuport_lama ?? '-'}}</td>
												<td>{{ $pengajuan->Tuport_baru ?? '-'}}</td>
											</tr>
										-->
											<tr>
												<td>Tunjangan Makan</td>
												<td>{{ $pengajuan->Tukan_lama }}</td>
												<td>{{ $pengajuan->Tukan_baru ?? '-' }}</td>
											</tr>
										</table>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-12">
                                <dl>
                                    <dt>Fasilitas Kerja</dt>
                                    <dd>
                                        @foreach ($facilities as $item)
                                            {{ $loop->iteration }}. {{ $item->Description }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p>Tanggal Diajukan : {{ $pengajuan->Insert_date ? date('d-m-Y', strtotime($pengajuan->Insert_date)):'-' }}</p>
                        <p>Tanggal Efektif : {{ $pengajuan->Eff_date ? date('d-m-Y', strtotime($pengajuan->Eff_date)):'-' }}</p>
                    </div>
                </div>
				@if ($pengajuan->flag_hpk == 1)
				<div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Hasil Penilaian Karyawan
                        </h4>
                    </div>
                    <div class="card-body">
                        <label for="">A. KEMAMPUAN</label>
										<table border='1' >
										<tr>
											<td colspan="2" >Faktor - Faktor Yang Dinilai:</td>
											<td>Nilai</td>
										</tr>
											<td>1.</td>
											<td>Pengetahuan dan Penguasaan terhadap pekerjaan</td>										
											@foreach ($penilaian as $nilai)
											<td align='center' >@if ($nilai->A1 == 1){{ "KS"  }} @endif
												@if ($nilai->A1 == 2){{ "K"  }} @endif
												@if ($nilai->A1 == 3){{ "C"  }} @endif
												@if ($nilai->A1 == 4){{ "B"  }} @endif
												@if ($nilai->A1 == 5){{ "BS"  }} @endif</td>
											@endforeach												
										<tr>
											<td>2.</td>
											<td>Ketekunan menghadapi pekerjaan</td>
											@foreach ($penilaian as $nilai)
											<td align='center' >@if ($nilai->A2 == 1){{ "KS"  }} @endif
												@if ($nilai->A2 == 2){{ "K"  }} @endif
												@if ($nilai->A2 == 3){{ "C"  }} @endif
												@if ($nilai->A2 == 4){{ "B"  }} @endif
												@if ($nilai->A2 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										</tr>
											<td>3.</td>
											<td>Mutu Pekerjaan</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->A3 == 1){{ "KS"  }} @endif
												@if ($nilai->A3 == 2){{ "K"  }} @endif
												@if ($nilai->A3 == 3){{ "C"  }} @endif
												@if ($nilai->A3 == 4){{ "B"  }} @endif
												@if ($nilai->A3 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										<tr>
											<td>4.</td>											
											<td>Inisiatif dan kreativitas dalam menjalankan tugas</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->A4 == 1){{ "KS"  }} @endif
												@if ($nilai->A4 == 2){{ "K"  }} @endif
												@if ($nilai->A4 == 3){{ "C"  }} @endif
												@if ($nilai->A4 == 4){{ "B"  }} @endif
												@if ($nilai->A4 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										</tr>
										<tr>
											<td>5.</td>
											<td>Kepemimpinan</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->A5 == 1){{ "KS"  }} @endif
												@if ($nilai->A5 == 2){{ "K"  }} @endif
												@if ($nilai->A5 == 3){{ "C"  }} @endif
												@if ($nilai->A5 == 4){{ "B"  }} @endif
												@if ($nilai->A5 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										</tr>
										</table>
										<label for="">B. SIKAP/ATTITUDE</label>
										<table border='1' >
										<tr>
											<td colspan="2" >Faktor - Faktor Yang Dinilai:</td>
											<td>Nilai</td>
										</tr>
											<td>1.</td>
											<td>Kerjasama dengan teman sekerja</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->B1 == 1){{ "KS"  }} @endif
												@if ($nilai->B1 == 2){{ "K"  }} @endif
												@if ($nilai->B1 == 3){{ "C"  }} @endif
												@if ($nilai->B1 == 4){{ "B"  }} @endif
												@if ($nilai->B1 == 5){{ "BS"  }} @endif
											</td>
											@endforeach	
										<tr>
											<td>2.</td>
											<td>Mengindahkan Instruksi Atasan</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->B2 == 1){{ "KS"  }} @endif
												@if ($nilai->B2 == 2){{ "K"  }} @endif
												@if ($nilai->B2 == 3){{ "C"  }} @endif
												@if ($nilai->B2 == 4){{ "B"  }} @endif
												@if ($nilai->B2 == 5){{ "BS"  }} @endif </td>
											@endforeach	
										</tr>
											<td>3.</td>
											<td>Interaksi dengan rekan kerja & lingkungan perusahaan</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->B3 == 1){{ "KS"  }} @endif
												@if ($nilai->B3 == 2){{ "K"  }} @endif
												@if ($nilai->B3 == 3){{ "C"  }} @endif
												@if ($nilai->B3 == 4){{ "B"  }} @endif
												@if ($nilai->B3 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										<tr>
											<td>4.</td>
											<td>Absensi dan hadir secara tepat waktu dan teratur</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->B4 == 1){{ "KS"  }} @endif
												@if ($nilai->B4 == 2){{ "K"  }} @endif
												@if ($nilai->B4 == 3){{ "C"  }} @endif
												@if ($nilai->B4 == 4){{ "B"  }} @endif
												@if ($nilai->B4 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										</tr>
										<tr>
											<td>5.</td>
											<td>Kedisiplinan dan menghargai waktu kerja</td>
											@foreach ($penilaian as $nilai)
											<td align='center'>@if ($nilai->B5 == 1){{ "KS"  }} @endif
												@if ($nilai->B5 == 2){{ "K"  }} @endif
												@if ($nilai->B5 == 3){{ "C"  }} @endif
												@if ($nilai->B5 == 4){{ "B"  }}  @endif
												@if ($nilai->B5 == 5){{ "BS"  }} @endif</td>
											@endforeach	
										</tr>
										</table><br>
										<div>Keterangan: <br> BS= Baik Sekali &nbsp; B= Baik &nbsp; C= Cukup &nbsp; K= Kurang &nbsp; KS= Kurang Sekali</div><br>
										 
										<div>
										<dl>
										@foreach ($penilaian as $nilai)
										<dt>Kekuatan</dt>
										<dd>{{ $nilai->kelebihan }}</dd>
										<dt>Kelemahan</dt>
										<dd>{{ $nilai->kekurangan }}</dd>
										@endforeach										
										</dl>
										</div>
                    </div>
                </div>
				@endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Status Approval
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $CurrentApproval = auth()->user()->employee;
							
                        @endphp
                        <h6>Approval Non HC</h6>
                        <ol>
						@foreach ($approval_log as $item)
                            @if($item->LevelId < 8 && $item->IsHc == 0 && $item->ApprovalSts > 0)
                                    <li><i class="fas fa-check-circle {{ $item->ApprovalSts == 1 ? 'text-success':'' }}"></i> <b>{{ $item->fullname }}</b>({{ $item->grade_title_name }})</li>
                            @endif
						@endforeach
                        </ol>
                        <h6>Approval HC</h6>
                        <ol>
						@foreach ($approval_log as $item)
                                @if($item->LevelId < 8 && $item->IsHc == 1 && $item->ApprovalSts > 0)
                                    <li><i class="fas fa-check-circle {{ $item->ApprovalSts == 1 ? 'text-success':'' }}"></i> <b>{{ $item->fullname }}</b>[{{ ($item->grade_title_name) }}]</li>
                                @endif
						@endforeach
                        </ol>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Riwayat Approval</h4>
                    </div>
                    <div class="card-body">
                        <ol class="activity-feed">
                            @foreach ($approval_log as $item)
							@if($item->LevelId < 8 && $item->ApprovalSts > 0)
                            <li class="feed-item feed-item-{{ $item->ApprovalSts == 1 ? 'success':'danger' }}">
                                <span class="text">{{ $item->ApprovalDate }}</span>
                                <br>
                                <span class="text">{{ $item->ApprovalSts == 1 ? 'Disetujui':'Ditolak' }} oleh <b>{{ $item->fullname }}</b> "{{ $item->ApprovalNotes }}"</span>
                            </li>
							@endif
                            @endforeach
                        </ol>
                    </div>
                </div>
				<!--
				@can('lampiran-fpk')
						@if ($pengajuan->lampiran != NULL)
						<div class="row">
							<div class="col-md-12">
									<div class="card-header">
                                        <h4 class="card-title">
                                            Lampiran FA
                                        </h4>
                                    </div>
                                    <div class="card-body">
										<a class="btn btn-outline-warning" href="{{ asset('uploads/' . $pengajuan->lampiran) }}" target="_blank">
											<i class="custom-icon fa fa-download"> Lampiran</i>
										</a>
									</div>
							</div>
						</div>
						@endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            Upload File
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('pengajuan.fpk.lampiran') }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Anda yakin mau upload file ini ?')">
                                            @csrf
                                            <input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
                                            <div class="form-group">
                                                <label for="">Add File</label>
                                                <input type="file" class="form-control" name="lampiran" required>
                                            </div>
                                            <button class="btn btn-primary"><i class="fa fa-file"></i> Submit File</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
						</div>
				@endcan
				-->
				@if($pengajuan->NextApproval == $EmployeeId && $pengajuan->ApprovedAll <> 2)
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Form Approval</h4>
						</div>
						<div class="card-body">
							<form action="{{ route('pengajuan.fpk.submit_approval') }}" method="post">
								@csrf
								<input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
								<input type="hidden" name="RequestorId" value="{{ $pengajuan->Insert_user }}">
								<input type="hidden" name="ApprovalBy" value="{{ $CurrentApproval->fullname }}">
								@foreach ($NextApproval as $Next)
								<input type="hidden" name="NextApproval" value="{{ $Next->id }}">
								<input type="hidden" name="LevelId" value="{{ $Next->grade_title_id }}">
								@endforeach
								<div class="form-group">
									<label for="">Approval Note</label>
									<textarea name="ApprovalNote" rows="3" class="form-control"></textarea>
								</div>
								<div class="form-group">
									<label for="">Status <span class="required-label">*</span></label>
									<select name="ApprovalSts" class="form-control selectpicker" required>
										<option></option>
										<option value="1">Setujui</option>
										<option value="2">Tolak</option>
									</select>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Submit</button>
								</div>
							</form>
						</div>
					</div>
				@endif

            </div>
        </div>
    </div>
    @endsection
    