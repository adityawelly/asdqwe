@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Data Pelamar</h4>
            {{ Breadcrumbs::render('data-pelamar') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Detail Pelamar</h4>
                        </div>
						<div class="d-flex flex-row-reverse">
							@if($pengajuan->status_data == 'BD')
							<a href="javascript:void(0)" onclick="approve({{ $pengajuan->id }}, 'TS')" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i> Tidak Sesuai</a> &nbsp;
							<a href="javascript:void(0)" onclick="approve({{ $pengajuan->id }}, 'WC')" class="btn btn-sm btn-warning"><i class="fas fa-user-tie"></i> Wawancara</a>&nbsp;
                            <a href="javascript:void(0)" onclick="approve({{ $pengajuan->id }}, 'TP')" class="btn btn-sm btn-success"><i class="fas fa-user-check"></i> Terpilih</a>&nbsp;
							@elseif($pengajuan->status_data == 'WC')
							<a href="javascript:void(0)" onclick="approve({{ $pengajuan->id }}, 'TS')" class="btn btn-sm btn-danger"><i class="fas fa-user-times"></i> Tidak Sesuai</a> &nbsp;
                            <a href="javascript:void(0)" onclick="approve({{ $pengajuan->id }}, 'TP')" class="btn btn-sm btn-success"><i class="fas fa-user-check"></i> Terpilih</a>&nbsp;
							@elseif($pengajuan->status_data == 'TS')
							<h4 align="right" style="color:red;">Tidak Sesuai</h4>
							@else
							<button class="btn btn-primary btn-round ml-auto" onclick="create()">
                                    <i class="fa fa-plus"></i>
                                    Tambah Karyawan
                            </button>
							<h4 align="right" style="color:green;">Terpilih</h4>
							@endif
						</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
								    <dt>Posisi Yang Dilamar</dt>
                                    <dd>{{ $pengajuan->job_title_name }}</dd>
                                    <dt>Nama Lengkap</dt>
                                    <dd>{{ $pengajuan->fullname }}</dd>
									<dt>No Identitas</dt>
                                    <dd>{{ $pengajuan->ktp ?? '-' }}</dd>
                                    <dt>Alamat Lengkap</dt>
                                    <dd>{{ $pengajuan->address }}</dd>
									<dt>Email</dt>
                                    <dd>{{ $pengajuan->email }}</dd>
                                    <dt>Telepon / HP</dt>
                                    <dd>{{ $pengajuan->phone }}</dd>
                                    <dt>Tempat & Tanggal Lahir</dt>
                                    <dd>{{ $pengajuan->place .' , '. $pengajuan->dob }}</dd>
									<dt>Jenis Kelamin</dt>
									@if($pengajuan->gender == 'M')
									<dd>Laki - Laki</dd>
									@else
									<dd>Perempuan</dd>
									@endif
									<dt>Status Perkawinan</dt>
									<dd>{{ $pengajuan->martial }}</dd>
									<dt>Agama</dt>
									<dd>{{ $pengajuan->religion }}</dd>
                                </dl>
							</div>
						    <div class="col-md-6">
								<dl>
									<dt>SIM</dt>
									@if($pengajuan->sim == 'AC')
									<dd>A & C</dd>
									@else
									<dd>{{ $pengajuan->sim }}</dd>
									@endif
									<dt>No. Kartu Keluarga</dt>
                                    <dd>{{ $pengajuan->kk ?? '-' }}</dd>
									<dt>NPWP</dt>
                                    <dd>{{ $pengajuan->npwp ?? '-' }}</dd>
                                    <dt>Pendidikan Terakhir</dt>
                                    <dd>{{ $pengajuan->lastedu }}<dd>
									<dt>Nama Institusi</dt>
                                    <dd>{{ $pengajuan->eduname }}<dd>
									<dt>Tahun Lulus</dt>
                                    <dd>{{ $pengajuan->yearedu }}<dd>
									<dt>Jurusan</dt>
                                    <dd>{{ $pengajuan->edufocus }}<dd>
									<dt>Prestasi Yang Pernah Dicapai</dt>
                                    <dd>{{ $pengajuan->prestasi ?? '-'}}<dd>
									<dt>Gaji Yang Diharapkan</dt>
                                    <dd>Rp. {{ number_format($pengajuan->salary) }}<dd>
									<dt>Siap Bekerja</dt>
                                    <dd>{{ $pengajuan->workstart ?? '-'}}<dd>
                                </dl>
                            </div>
                            <div class="col-md-12">
								<dl>
                                    <dt>Pengalaman Kerja</dt>
									@if($pengajuan->companyname1 != null)
                                    <table class="table-bordered" width="100%">
									<tr>
									<th>Nama Perusahaan</th>
									<th>Bagian / Jabatan</th>
									<th>Lama Bekerja</th>
									<th>Alasan Berhenti</th>
									</tr>
									<tr>
									<td>{{ $pengajuan->companyname1 }}</td>
									<td>{{ $pengajuan->lastpostion1 }}</td>
									<td>{{ $pengajuan->sdate1 .' s/d '. $pengajuan->edate1 }}</td>
									<td>{{ $pengajuan->reason1 }}</td>
									</tr>
									@if($pengajuan->companyname2 != null)
									<tr>
									<td>{{ $pengajuan->companyname2 }}</td>
									<td>{{ $pengajuan->lastpostion2 }}</td>
									<td>{{ $pengajuan->sdate2 .' s/d '. $pengajuan->edate2 }}</td>
									<td>{{ $pengajuan->reason2 }}</td>
									</tr>
									@endif
									@if($pengajuan->companyname3 != null)
									<tr>
									<td>{{ $pengajuan->companyname3 }}</td>
									<td>{{ $pengajuan->lastpostion3 }}</td>
									<td>{{ $pengajuan->sdate3 .' s/d '. $pengajuan->edate3 }}</td>
									<td>{{ $pengajuan->reason3 }}</td>
									</tr>
									@endif
									</table>
									@else
									<dd>Fresh Graduated<dd>
									@endif
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
						<p>Unduh File Lamaran</p>
                        <a class="btn btn-outline-success" href="{{URL::to('https://job-portal.niramasutama.com/pdf/' . $pengajuan->file_name)}}" target="_blank">
						<i class="custom-icon fa fa-download">Unduh</i></a>
                    </div>
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

		var modal = $('#pageModal');
		var form = $('#formModal');

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

	function approve(id, val) {
        swal({
            titleText: 'Apakah anda yakin?',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-danger',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.value) {
                do_approve(id, val);
            }
        });
    }

	$('#btnSubmit').on('click', function(e){
        if (form.valid()) {
            $(this).addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    })

	function create() {

		form.attr('action', '{{ route('employee.store') }}');
        modal.find('.modal-title').text('Tambah Karyawan');
        form.find('input[name=_method]').remove();
        modal.modal('toggle');
    }

    function do_approve(id, val) {
        $.ajax({
            url: '{{ url('apply') }}/'+id+'/approve',
            type: 'POST',
            dataType: 'JSON',
            data: {
                status: val,
            },
            success: function(resp){
                showNotification('info', 'Halaman akan direfresh...');
                location.reload();
            },
            error: (error)=>{
                console.error(error);
                showSwal('error', 'Terjadi Kesalahan', 'Silahkan refresh dan coba lagi');
            }
        });
    }
    </script>
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
						@php
						  if($pengajuan->gender == 'M')
						  {
							  $gender='Laki - Laki';
						  }
						  else {
							  $gender='Perempuan';
						  }
						  $xreligi = strtolower($pengajuan->religion);
						  $nreligi = ucfirst($xreligi);


						@endphp
						<div class="form-group">
							 <input type="hidden" name="idx" value="{{ $pengajuan->id }}">
							 <input type="hidden" name="level_title_id" value="{{ $pengajuan->level_id }}">
							 <input type="hidden" name="level" value="{{ $pengajuan->level_id }}">
							 <input type="hidden" name="division_id" value="{{ $pengajuan->division_id }}">
							 <input type="hidden" name="department_id" value="{{ $pengajuan->dept_id }}">
							 <input type="hidden" name="job_title_id" value="{{ $pengajuan->job_id }}">
							 <input type="hidden" name="company_region_id" value="{{ $pengajuan->region_id }}">
							 <input type="hidden" name="place_of_birth" value="{{ $pengajuan->place }}">
							 <input type="hidden" name="date_of_birth" value="{{ $pengajuan->dob }}">
							 <input type="hidden" name="ID_number" value="{{ $pengajuan->ktp }}">
							 <input type="hidden" name="sex" value="{{ $gender }}">
							 <input type="hidden" name="religion" value="{{ $nreligi }}">
							 <input type="hidden" name="phone_number" value="{{ $pengajuan->phone }}">
							 <input type="hidden" name="npwp" value="{{ $pengajuan->npwp }}">
							 <input type="hidden" name="last_education" value="{{ $pengajuan->lastedu }}">
							 <input type="hidden" name="education_focus" value="{{ $pengajuan->eduname }}">
							 <input type="hidden" name="address" value="{{ $pengajuan->address }}">
                        </div>
                        <div class="form-group">
                             <label for="">Nomor Induk Kepegawaian <span class="required-label">*</span></label>
                             <input type="text" class="form-control nik-mask" name="registration_number" required minlength="5">
                        </div>
						<div class="form-group">
                             <label for="">Nama Karyawan<span class="required-label">*</span></label>
                             <input type="text" class="form-control" name="fullname" value="{{ $pengajuan->fullname }}">
                        </div>
						<div class="form-group">
                             <label for="">Grade <span class="required-label">*</span></label>
                             <select name="grade" class="form-control selectpicker" style="width: 100%" required>
                                <option></option>
                                    @foreach ($gradeOptions as $item)
                                    <option value="{{ $item }}">{{ strtoupper($item) }}</option>
                                    @endforeach
                             </select>
                        </div>
						<div class="form-group">
                             <label for="">Grade Title <span class="required-label">*</span></label>
                             <select name="grade_title_id" class="form-control selectpicker" style="width: 100%" required>
                                <option></option>
                                    @foreach ($grade_titleOptions as $option)
                                    <option value="{{ $option->id }}">{{ strtoupper($option->grade_title_code.'-'.$option->grade_title_name) }}</option>
                                    @endforeach
                             </select>
                        </div>
                        <div class="form-group">
                             <label for="">Tanggal Mulai Bekerja <span class="required-label">*</span></label>
                             <input type="text" class="form-control datepicker" name="date_of_work" required>
                        </div>
						<div class="form-group">
                             <label for="">Status Perkawinan <span class="required-label">*</span></label>
                             <select class="form-control selectpicker" name="marital_status" style="width: 100%" required>
                                    <option></option>
                                        @foreach ($marital_statusOptions as $option)
                                        <option value="{{ $option['value'] }}">{{ strtoupper($option['view']) }}</option>
                                        @endforeach
                             </select>
                        </div>
						<div class="form-group">
                             <label for="">Status Kerja <span class="required-label">*</span></label>
                                <select name="status" class="form-control selectpicker" style="width: 100%" required>
                                    <option></option>
                                    @foreach ($statusOptions as $option)
                                       <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                    @endforeach
                           </select>
                       </div>
                       <div class="form-group">
                              <label for="">Nama Ibu Kandung <span class="required-label">*</span></label>
                              <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                       </div>
					   <div class="form-group">
                              <label for="">Gaji Pokok dalam (RP) <span class="required-label">*</span></label>
                              <input type="text" class="form-control money-mask" name="basic_salary" id="basic_salary" required>
                       </div>
                       <div class="form-group">
                             <label for="">Pembayaran Tiap <span class="required-label">*</span></label>
                             <select class="form-control selectpicker" id="payroll_type" name="payroll_type" style="width: 100%" required>
                             <option></option>
                                 @foreach ($payroll_typeOptions as $option)
                                      <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                 @endforeach
                             </select>
                       </div>
					   <div class="form-group">
                            <label for="">Uang Makan <span class="required-label">*</span></label>
                            <select class="form-control selectpicker" id="meal_allowance" name="meal_allowance" style="width: 100%" required>
                               <option></option>
                                   @foreach ($meal_allowanceOptions as $option)
                                       <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                   @endforeach
                            </select>
                       </div>
                       <div class="form-group">
                             <label for="">Sumber Gaji <span class="required-label">*</span></label>
                             <select class="form-control selectpicker" id="salary_post" name="salary_post" style="width: 100%" required>
                                 <option></option>
                                     @foreach ($salary_postOptions as $option)
                                          <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                     @endforeach
                                 </select>
                       </div>
                       <div class="form-group">
                           <label for="">Bank</label>
                           <select name="bank" id="bank" class="form-control selectpicker" style="width: 100%">
                                <option></option>
                                    @foreach ($bankOptions as $option)
                                        <option value="{{ $option }}">{{ strtoupper($option) }}</option>
                                    @endforeach
                                </select>
                           </div>
                        <div class="form-group">
                            <label for="">Nomor Rekening</label>
						    <input type="text" class="form-control" name="bank_account_number">
                        </div>
                        <div class="form-group">
                            <label for="">Email <span class="required-label">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="">Password <span class="required-label">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <small class="form-text text-muted">*Minimal 6 karakter huruf dan atau angka</small>
                        </div>
                        <div class="form-group">
                            <label for="">Role <span class="required-label">*</span></label>
                            <select class="form-control selectpicker" id="role" name="role" style="width: 100%" required>
                               <option></option>
                                   @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                   @endforeach
                             </select>
                        </div>
                        <div class="form-group">
                            <label for="">Upload foto karyawan</label>
                            <div class="input-file input-file-image">
                                 <img class="img-upload-preview img-circle" width="150" height="150" src="{{ asset('uploads/images/profile-avatar-flat.png') }}" alt="preview">
                                 <input type="file" class="form-control form-control-file" id="photo" name="photo" accept="image/*">
                                 <label for="photo" class="btn btn-secondary btn-round btn-sm"><i class="fa fa-file-image"></i> Upload a Image</label>
                                 <small class="form-text text-muted">*Maksimal 512kb, format image(.jpg/.png)</small>
                           </div>
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
