@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Form Penilaian Karyawan</h4>
            {{ Breadcrumbs::render('PAFORM') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">PENILAIAN KINERJA TAHUNAN</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nomor</dt>
                                    <dd></dd>
									@foreach ($edited as $edited)
                                    <dt>Nama Karyawan</dt>
                                    <dd>{{ $edited->employee_id .' - '.$edited->fullname }}</dd>
									@endforeach
                                    <dt>Tgl. Penilaian</dt>
                                    <dd>{{ date('d-m-Y', strtotime($pengajuan->UpdatedDate)) }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Jabatan</dt>
                                    <dd>{{ $edited->job_title_name }}</dd>
                                    <dt>Departement</dt>
                                    <dd>{{ $edited->department_name }}</dd>
                                    <dt>Penilai</dt>
                                    <dd>{{ $edited->nama_atasan }}</dd>                               
                                </dl>
                            </div> 
							<div class="col-md-12">
                                <dl>
                                    <dt>Data Penilaian</dt>
                                    <dd>													
										<label for="">A. KEMAMPUAN</label>
										<table width="100%" class="table table-bordered" >
										<tr>
											<td width="10%">BBT</td>
											<td width="20%">PARAMETER</td>
											<td width="53%"></td>
											<td width="12%">NILAI</td>
										</tr>
										@foreach($inserted as $item)
										<tr>
											<td>{{ $item->PaParamsBobot }} %</td>
											<td>{{ $item->Namasub }}</td>
											@php
                                                $subbab = DB::select("select * from pa_params where SubbabId = $item->PaParamsId");
                                            @endphp
											<td>										
											<table class="table table-borderless">
												@foreach ($subbab as $items)
												<tr>
													<td>{{ $items->Parameters }}</td>
													<td>{{ $items->Nilai }}</td>
												</tr>
												@endforeach
											</table>
											</td>
											<td>{{ $item->PaParamsScore }}</td>
										</tr>
										@endforeach
										<tr><td colspan="3">Total Nilai</td><td>{{ number_format($pengajuan->PaScore,2) }}</td></tr>
										@if($edited->grade_title_id != 5)
										<tr><td colspan="3">Total KPI</td><td>{{ number_format($pengajuan->kpi,2) }}</td></tr>
										<tr><td colspan="3">Total</td><td>{{ number_format($pengajuan->skor,2) }}</td></tr>
										@endif
										</table>
										<div>Range: <br> 4.01 - 5.00 = A <br> 3.01 - 4.00 = B <br> 2.01 - 3.00 = C <br> 1.01 - 2.00 = D <br> 0.00 - 1.00 = E</div>																	</div>
                                    </dd>
                                </dl>
                            </div>

                        </div>
                    </div>
                
				
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Status Approval
                        </h4>
					</div>
					<div class="card-body">
                        <ol>
                       @foreach ($approval_log as $item)
                            @if($item->ApprovalSts > 0)
                                    <li><i class="fas fa-check-circle {{ $item->ApprovalSts == 1 ? 'text-success':'' }}"></i> <b>{{ $item->fullname }}</b>({{ $item->grade_title_name }})</li>
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
								<li class="feed-item feed-item-{{ $item->ApprovalSts == 1 ? 'success':'danger' }}">
									<span class="text">{{ $item->ApprovalDate }}</span>
									<br>
									<span class="text">{{ $item->ApprovalSts == 1 ? 'Disetujui':'Ditolak' }} oleh <b>{{ $item->fullname }}</b> "{{ $item->ApprovalNotes }}"</span>
								</li>
								@endforeach
							</ol>
						</div>
					</div>
					@php
                         $EmployeeId = auth()->user()->employee->id;
					@endphp
					@if($pengajuan->NextApproval == $EmployeeId && $pengajuan->ApprovedAll <> 2)
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Form Approval</h4>
						</div>
						<div class="card-body">
							<form action="{{ route('PAForm.submit_approval') }}" method="post">
								@csrf
								<input type="hidden" name="PaId" value="{{ $pengajuan->PaId }}">
								@foreach ($NextApproval as $Next)
								<input type="hidden" name="NextApproval" value="{{ $Next->direct_superior }}">
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
    </div>
    @endsection
    