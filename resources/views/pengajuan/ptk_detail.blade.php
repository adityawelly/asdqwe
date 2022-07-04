@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail PTK</h4>
            {{ Breadcrumbs::render('pengajuan-ptk') }}
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
                                    <dt>Nomor PTK</dt>
                                    <dd>{{ $pengajuan->ReqNo ?? '-' }}</dd>
                                    <dt>Job Title</dt>
                                    <dd>{{ $pengajuan->job_title_name }}</dd>
                                    <dt>Level Jabatan</dt>
                                    <dd>{{ $pengajuan->grade_title_name }}</dd>
                                    <dt>Departemen</dt>
                                    <dd>{{ $pengajuan->department_name }}</dd>
                                    <dt>Grade</dt>
                                    <dd>{{ $pengajuan->Grade }}</dd>
                                    <dt>Level</dt>
                                    <dd>{{ $pengajuan->Level }}</dd>
                                    <dt>Lokasi Kerja</dt>
                                    <dd>{{ $pengajuan->region_city }}</dd>
                                    <dt>Status Karyawan</dt>
                                    <dd>{{ $pengajuan->empStatus }} ({{ $pengajuan->EmploymentNote ?? '-' }})</dd>
                                    <dt>Sistem Kerja</dt>
                                    <dd>{{ $pengajuan->WorkingTime }}</dd>
                                    <dt>Usia</dt>
                                    <dd>{{ $pengajuan->MinAge.'-'.$pengajuan->MaxAge }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Jumlah Dibutuhkan</dt>
                                    <dd>{{ $pengajuan->ReqQty }}</dd>
                                    <dt>Jumlah Laki-laki</dt>
                                    <dd>{{ $pengajuan->QtyMale ?? '-' }}</dd>
                                    <dt>Jumlah Perempuan</dt>
                                    <dd>{{ $pengajuan->QtyFemale ?? '-' }}</dd>
                                    <dt>Jumlah Laki-laki/Perempuan (Both)</dt>
                                    <dd>{{ $pengajuan->QtyBoth ?? '-' }}</dd>
                                    <dt>Pendidikan</dt>
                                    <dd>{{ $pengajuan->Education }}</dd>
                                    <dt>Jurusan</dt>
                                    <dd>{{ $pengajuan->EducationFocus ?? '-' }}</dd>
                                    <dt>Pengalaman Kerja</dt>
                                    <dd>{{ $pengajuan->WorkingExperience }}</dd>
                                    <dt>Tanggal Mulai Kerja</dt>
                                    <dd>{{ $pengajuan->ActiveDate }}</dd>
                                    <dt>Alasan Perekrutan</dt>
                                    <dd>
                                        {{ $pengajuan->reasonDesc }}
                                        @if (in_array($pengajuan->ReasonOfHiring, ['ReplcMut', 'ReplcRsgn']))
                                            <br>
                                            @foreach ($replacements as $item)
                                                {{ $loop->iteration }}. {{ $item->EmployeeReplaced.' diganti oleh '.$item->EmployeeReplacement }} <br>
                                            @endforeach
                                        @endif
                                    </dd>
                                    <dt>Catatan</dt>
                                    <dd>{{ $pengajuan->Notes}}</dd>
                                    <dt>Atasan Langsung</dt>
                                    <dd>{{ $pengajuan->fullname }} ({{ $pengajuan->registration_number }})</dd>
                                    <dt>Diminta Oleh</dt>
                                    <dd>{{ $pengajuan->fullname }} ({{ $pengajuan->registration_number }})</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Keahlian Khusus</dt>
                                    <dd>
                                        @foreach ($skill_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->SkillDesc ?? '-' }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Uraian Pekerjaan</dt>
                                    <dd>
                                        @foreach ($job_desc as $item)
                                            {{ $loop->iteration }}. {{ $item->JobDesc ?? '-' }} <br>
                                        @endforeach
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-12">
                                <dl>
                                    <dt>Persiapan Peralatan dan Fasilitas Kerja</dt>
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
                        <p>Tanggal Deadline : {{ $pengajuan->Deadline ? date('d-m-Y', strtotime($pengajuan->Deadline)):'-' }}</p>
                        <p>Tanggal Pemenuhan : {{ $pengajuan->FilledDate ? date('d-m-Y', strtotime($pengajuan->FilledDate)):'-' }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Status Approval
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $NextApproval = 0;
                            $CurrentApproval = auth()->user()->employee;
                        @endphp
                        <h6>Approval Non HC</h6>
                        <ol>
                            @foreach ($approval_data as $item)
                                @if($item->IsHc == 0)
                                    <li><i class="fas fa-check-circle {{ $item->ApprovedFlag == 1 ? 'text-success':'' }}"></i> <b>{{ $item->fullname }}</b>({{ $item->grade_title_name }})</li>
                                @endif
                                @php
                                    if ($item->EmployeeIdApproval == $CurrentApproval->id) {
                                        if ((array_key_exists($loop->index+1, $approval_data))) {
                                            $NextApproval = $approval_data[$loop->index+1]->EmployeeIdApproval;
                                        }
                                    }
                                @endphp
                            @endforeach
                        </ol>
                        <h6>Approval HC</h6>
                        <ol>
                            @foreach ($approval_data as $item)
                                @if($item->IsHc == 1)
                                    <li><i class="fas fa-check-circle {{ $item->ApprovedFlag == 1 ? 'text-success':'' }}"></i> <b>{{ $item->fullname }}</b>({{ $item->grade_title_name }})</li>
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
                @if ($pengajuan->ReqSts != 1)
                    @can('modify-ptk')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            Tutup Pengajuan
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('pengajuan.ptk.close') }}" method="post" onsubmit="return confirm('Anda yakin, hal ini tidak akan bisa dikembalikan ?')">
                                            @csrf
                                            <input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
                                            <div class="form-group">
                                                <label for="">Tanggal Pemenuhan</label>
                                                <input type="text" class="form-control datepicker" name="FilledDate" required>
                                            </div>
                                            <button class="btn btn-danger"><i class="fa fa-times"></i> Tutup Pengajuan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            Outstanding Pengajuan
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('pengajuan.ptk.outstanding') }}" method="post" onsubmit="return confirm('Anda yakin, pastikan data anda sudah benar ?')">
                                            @csrf
                                            <input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
                                            <div class="form-group">
                                                <label for="">Nomor PTK</label>
                                                <input type="text" class="form-control" name="ReqNo" value="{{ $pengajuan->ReqNo }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tanggal Deadline</label>
                                                <input type="text" class="form-control datepicker" name="Deadline" value="{{ $pengajuan->Deadline }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Outstanding Laki-laki</label>
                                                <input type="number" class="form-control" name="OutStandMale" value="{{ $pengajuan->OutStandMale }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Outstanding Perempuan</label>
                                                <input type="number" class="form-control" name="OutStandFemale" value="{{ $pengajuan->OutStandFemale }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Total Outstanding</label>
                                                <input type="number" class="form-control" name="OutStandBoth" value="0" disabled>
                                            </div>
                                            <button class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif

                @if(($FlagApproval == 1 && $pengajuan->ReqSts == 0))
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Approval</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengajuan.ptk.submit_approval') }}" method="post">
                            @csrf
                            <input type="hidden" name="ReqId" value="{{ $pengajuan->ReqId }}">
                            <input type="hidden" name="ReqNo" value="{{ $pengajuan->ReqNo }}">
                            <input type="hidden" name="ApprovalBy" value="{{ $CurrentApproval->fullname }}">
                            <input type="hidden" name="FlagIsCurrentHC" value="{{ $FlagIsCurrentHC }}">
                            <input type="hidden" name="RequestorId" value="{{ $pengajuan->EmployeeIdRequestor }}">
                            <input type="hidden" name="RequestorName" value="{{ $pengajuan->fullname }}">
                            <input type="hidden" name="NextApproval" value="{{ $NextApproval }}">
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
				
				@if($pengajuan->ReqSts == 1)
				<div class="card">
                    <div class="card-header">
						<button class="btn btn-primary btn-sm" onclick="create()">
										<i class="fa fa-plus">Buat PKWT</i>
										
						</button>
					</div>
				    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pkwt-table" class="display table table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th>No PKWT</th>
                                        <th>Nama Karyawan</th>
                                        <th>Kontrak Ke</th>
										<th>Lama Kontrak</th>
                                        <th>Tanggal Kontrak</th>
                                        <th>Job Title</th>                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pkwt as $pkwt)
                                        <tr>
                                            <td>{{ $pkwt->pkwt_no }}</td>
                                            <td>{{ $pkwt->employee->fullname }}</td>
                                            <td>{{ $pkwt->kontrak_ke }}</td>
											<td>{{ $pkwt->bulan .' Bulan' }}</td>
                                            <td>{{ tgl_indo($pkwt->sdate) .' s/d '. tgl_indo($pkwt->edate) }}</td>
                                            <td>{{ $pkwt->job_title_id .' - '. $pkwt->job->job_title_name }}</td>										
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
				</div>
				@endif
            </div>
        </div>
    </div>
    @endsection
	@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="pageModal" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modal">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="formModal">
                        @csrf
						<input type="hidden" name="fpk_id" value="{{ $pengajuan->ReqId }}">
						<input type="hidden" name="no_reff" value="{{ $pengajuan->ReqNo }}">
                        <div class="form-group">
                            <label for="">Pilih Karyawan<span class="required-label">*</span></label>
                            <select name="employee_id" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                 @foreach ($employee as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->registration_number.'-'.$employee->fullname }}</option>
                                 @endforeach
                            </select>
                        </div>
						<div class="form-group">
                            <label for="">Lama Kontrak<span class="required-label">*</span></label>
                            <select name="bulan" class="form-control selectpicker" style="width:100%">
                                <option></option>
                                 @foreach ($masa_kontrak as $item)
								   <option value="{{ $item }}">{{ $item }} Bulan</option>
								 @endforeach
                            </select>
                        </div>
                        <div class="form-group">					
                            <label for="">Tanggal Awal Kontrak<span class="required-label">*</span></label>
                            <input type="text" name="sdate" class="form-control datepicker">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Akhir Kontrak<span class="required-label">*</span></label>
                            <input type="text" name="edate" class="form-control datepicker">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
	@endsection

    @section('script')
    <script>
        var dt = $('#dttable').dataTable({
            // responsive: true,
        }).api();

        $(document).ready(function(){
            countBoth();
        });

        $('form').on('submit', function(){
            $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
        });

        var OutStandMale = $('input[name=OutStandMale]');
        var OutStandFemale = $('input[name=OutStandFemale]');

        function countBoth() {
            var firstVal = OutStandMale.val();
            var secondVal = OutStandFemale.val();
            firstVal = firstVal == '' ? 0:parseInt(firstVal);
            secondVal = secondVal == '' ? 0:parseInt(secondVal);
            $('input[name=OutStandBoth]').val(firstVal+secondVal);
        }

        OutStandMale.on('input', function(){
            countBoth();
        });

        OutStandFemale.on('input', function(){
            countBoth();
        });
	
	var modal = $('#pageModal');
    var form = $('#formModal');	
	
	 $('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })
	
	function create() {
        form.attr('action', '{{ url('ListPKWT') }}');
        modal.find('.modal-title').text('Buat PKWT');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }
	
	

    function edit(id, el) {
        form.attr('action', '{{ url('ListPKWT') }}/'+id);
        $.ajax({
            url: '{{ url('ListPKWT') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                $(el).addClass('is-loading').attr('disabled', true);
            },
            success: function(resp){
                form.find('select[name=employee_id]').val(resp.employee_id).trigger('change');
                form.find('input[name=sdate]').val(resp.sdate);
                form.find('input[name=edate]').val(resp.edate);
                modal.find('.modal-title').text('Edit PKWT');
                form.append('@method('PUT')');
                modal.modal('toggle');
            },
            error: function(error){
                console.error(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            },
            complete: function(){
                $(el).removeClass('is-loading').attr('disabled', false);
            }
        })
    }
    </script>
    @endsection