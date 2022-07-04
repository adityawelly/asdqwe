@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Karyawan</h4>
            {{ Breadcrumbs::render('karyawan-edit', $employee->id) }}
        </div>
        <div class="row">
            <div class="col-md-9 ml-auto mr-auto">
                @include('layouts.partials.alert')
            </div>
        </div>
        <div class="row">
            <div class="wizard-container wizard-round col-md-9">
                <div class="wizard-header text-center">
                    <h3 class="wizard-title"><b>Formulir</b> Karyawan</h3>
                    <small>Mohon isi dengan baik dan benar</small>
                </div>
                <form id="employeeForm" action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="wizard-body">
                        <div class="row">
                            <ul class="wizard-menu nav nav-pills nav-primary">
                                <li class="step" style="width: 25%;">
                                    <a class="nav-link active" href="#company" data-toggle="tab" aria-expanded="true">
                                        <i class="fa fa-building mr-0"></i> Data Perusahaan</a>
                                </li>
                                <li class="step" style="width: 25%;">
                                    <a class="nav-link" href="#account" data-toggle="tab">
                                        <i class="fa fa-user mr-2"></i> Data Pribadi</a>
                                </li>
                                <li class="step" style="width: 25%;">
                                    <a class="nav-link" href="#salary" data-toggle="tab">
                                        <i class="fas fa-dollar-sign mr-2"></i> Data Penggajian</a>
                                </li>
                                <li class="step" style="width: 25%;">
                                    <a class="nav-link" href="#secret" data-toggle="tab">
                                        <i class="fas fa-user-secret mr-2"></i> Data User</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="company">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="info-text">Berkaitan dengan perusahaan. *(wajib diisi)</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Nomor Induk Kepegawaian <span class="required-label">*</span></label>
                                            <input value="{{ $employee->registration_number }}" type="text" class="form-control nik-mask" name="registration_number" required minlength="5" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Mulai Bekerja <span class="required-label">*</span></label>
                                            <input value="{{ $employee->date_of_work }}" type="text" class="form-control datepicker" name="date_of_work" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nama Lengkap (Tidak dengan gelar) <span class="required-label">*</span></label>
                                            <input value="{{ $employee->fullname }}" type="text" class="form-control" name="fullname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Grade <span class="required-label">*</span></label>
                                            <select name="grade" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($gradeOptions as $item)
                                                    <option {{ $item == $employee->grade ? 'selected':'' }} value="{{ $item }}">{{ strtoupper($item) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Level <span class="required-label">*</span></label>
                                            <select name="level" class="form-control" required>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Kerja <span class="required-label">*</span></label>
                                            <select name="status" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($statusOptions as $option)
                                                    <option {{ $option == $employee->status ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Divisi <span class="required-label">*</span></label>
                                            <select name="division_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($divisionOptions as $option)
                                                    <option {{ $option->id == $employee->division_id ? 'selected':'' }} value="{{ $option->id }}">{{ strtoupper($option->division_code.'-'.$option->division_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Departemen <span class="required-label">*</span></label>
                                            <select name="department_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($departmentOptions->where('division_id', $employee->division_id)->all() as $option)
                                                    <option {{ $option->id == $employee->department_id ? 'selected':'' }} value="{{ $option->id }}">{{ strtoupper($option->department_code.'-'.$option->department_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Job Title <span class="required-label">*</span></label>
                                            <select name="job_title_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($job_titleOptions as $option)
                                                    <option {{ $option->id == $employee->job_title_id ? 'selected':'' }} value="{{ $option->id }}">{{ strtoupper($option->job_title_code.'-'.$option->job_title_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Lokasi Kerja <span class="required-label">*</span></label>
                                            <select name="company_region_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($company_regionOptions as $option)
                                                    <option {{ $option->id == $employee->company_region_id ? 'selected':'' }} value="{{ $option->id }}">{{ strtoupper($option->region_city) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Grade Title <span class="required-label">*</span></label>
                                            <select name="grade_title_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($grade_titleOptions as $option)
                                                    <option {{ $option->id == $employee->grade_title_id ? 'selected':'' }} value="{{ $option->id }}">{{ strtoupper($option->grade_title_code.'-'.$option->grade_title_name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Level Title <span class="required-label">*</span></label>
                                            <select name="level_title_id" class="form-control selectpicker" required>
                                                <option></option>
                                                @foreach ($level_titleOptions as $level)
                                                    <optgroup label="{{ $level[0]->level_title_type }}">
                                                        @foreach ($level as $option)
                                                            <option {{ $option->id == $employee->level_title_id ? 'selected':'' }} value="{{ $option->id }}">{{ $option->level_title_code.'-'.$option->level_title_name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="account">
                                <h4 class="info-text">Berkaitan dengan data diri pekerja.</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="place_of_birth">Tempat Lahir <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->place_of_birth }}" type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_of_birth" class="form-control-label">Tanggal Lahir <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->date_of_birth }}" type="text" class="form-control datepicker" id="date_of_birth" name="date_of_birth" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ID_number">No KTP <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->ID_number }}" type="text" class="form-control ktp-mask" id="ID_number" name="ID_number" minlength="16" maxlength="16" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mother_name">Nama Ibu Kandung <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->mother_name }}" type="text" class="form-control" id="mother_name" name="mother_name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="marital_status">Status Perkawinan <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="marital_status" name="marital_status" required>
                                                <option></option>
                                                @foreach ($marital_statusOptions as $option)
                                                    <option {{ $option['value'] == $employee->employee_detail->marital_status ? 'selected':'' }} value="{{ $option['value'] }}">{{ strtoupper($option['view']) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sex">Jenis Kelamin <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="sex" name="sex" required>
                                                <option></option>
                                                @foreach ($sexOptions as $option)
                                                    <option {{ $option == $employee->employee_detail->sex ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="religion">Agama <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="religion" name="religion" required>
                                                <option></option>
                                                @foreach ($religionOptions as $option)
                                                    <option {{ $option == $employee->employee_detail->religion ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number">Nomor Telepon <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->phone_number }}" type="text" class="form-control phone-mask" id="phone_number" name="phone_number" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="npwp">NPWP <span class="required-label">*</span></label>
                                            <input value="{{ $employee->employee_detail->npwp }}" type="text" class="form-control npwp-mask" id="npwp" name="npwp" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_education">Pendidikan Terakhir <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="last_education" name="last_education" required>
                                                <option></option>
                                                @foreach ($last_educationOptions as $option)
                                                    <option {{ $option == $employee->employee_detail->last_education ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="education_focus">Jurusan</label>
                                            <input type="text" class="form-control" name="education_focus" value="{{ $employee->employee_detail->education_focus }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Alamat Sesuai KTP <span class="required-label">*</span></label>
                                            <textarea class="form-control" name="address" id="address" rows="3" required>{{ $employee->employee_detail->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="salary">
                                <h4 class="info-text">Berkaitan tentang payroll karyawan</h4>
                                <div class="row">
                                    <div class="col-md-9 ml-auto mr-auto">
                                        @can('update-salary')
                                        <div class="form-group">
                                            <label for="basic_salary">Gaji Pokok dalam (RP) <span class="required-label">*</span></label>
                                            <input type="text" class="form-control money-mask" name="basic_salary" id="basic_salary" value="{{ $employee->employee_salary->basic_salary }}" required>
                                        </div>
                                        @endcan
                                        <div class="form-group">
                                            <label for="payroll_type">Pembayaran Tiap <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="payroll_type" name="payroll_type" required>
                                                <option></option>
                                                @foreach ($payroll_typeOptions as $option)
                                                    <option {{ $option == $employee->employee_salary->payroll_type ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="meal_allowance">Uang Makan <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="meal_allowance" name="meal_allowance" required>
                                                <option></option>
                                                @foreach ($meal_allowanceOptions as $option)
                                                    <option {{ $option == $employee->employee_salary->meal_allowance ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="salary_post">Sumber Gaji <span class="required-label">*</span></label>
                                            <select class="form-control selectpicker" id="salary_post" name="salary_post" required>
                                                <option></option>
                                                @foreach ($salary_postOptions as $option)
                                                    <option {{ $option == $employee->employee_salary->salary_post ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bank</label>
                                            <select name="bank" id="bank" class="form-control selectpicker">
                                                <option></option>
                                                @foreach ($bankOptions as $option)
                                                    <option {{ $option == $employee->employee_salary->bank ? 'selected':'' }} value="{{ $option }}">{{ strtoupper($option) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Rekening</label>
                                            <input type="text" class="form-control" name="bank_account_number" value="{{ $employee->employee_salary->bank_account_number }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="secret">
                                <h4 class="info-text">Berkaitan tentang data user</h4>
                                <div class="row">
                                    <div class="col-md-9 ml-auto mr-auto">
                                        <div class="form-group">
                                            <label for="">Email <span class="required-label">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $employee->user->email }}" required>
                                        </div>  
                                        <div class="form-group">
                                            <label for="">Password <span class="required-label">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password" minlength="6" required placeholder="Kosongkan jika tidak ingin diubah">
                                            <small class="form-text text-muted">*Minimal 6 karakter huruf dan atau angka</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Upload foto karyawan</label>
                                            <div class="input-file input-file-image">
                                                <img class="img-upload-preview img-circle" width="150" height="150" src="{{ asset('uploads/images/users/'.($employee->photo ?? '../profile-avatar-flat.png')) }}" alt="preview">
                                                <input type="file" class="form-control form-control-file" id="photo" name="photo" accept="image/*">
                                                <label for="photo" class="btn btn-secondary btn-round btn-sm"><i class="fa fa-file-image"></i> Upload a Image</label>
                                                <small class="form-text text-muted">*Maksimal 512kb, format image(.jpg/.png)</small>
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-action">
                        <div class="pull-left">
                            <button type="button" class="btn btn-previous btn-default" name="previous"><i class="fas fa-chevron-circle-left"></i> Kembali</button>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-next btn-primary" name="next">Lanjut <i class="fas fa-chevron-circle-right"></i></button>
                            <button type="submit" class="btn btn-finish btn-success" name="finish" style="display: none;"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function passwordRequired() {
        return $('input[name=password]').val().length > 0;
    }
    var $validator = $('.wizard-container form').validate({
        ignore: ':hidden, .select2-input, .select2-focusser',
        validClass : "success",
        rules: {
            password:{
                required: passwordRequired,
                minlength: {
                    param: 6,
                    depends: passwordRequired
                }
            },
        },
        submitHandler: function(form){

            var originalForm = $('#employeeForm');
            var buttonSubmit = $('button[name=finish]');

            $.ajax({
                url: originalForm.attr('action'),
                type: 'POST',
                data: new FormData(form),
                dataType: 'JSON',
                beforeSend: function(){
                    buttonSubmit.addClass('is-loading').attr('disabled', true);
                },
                processData: false,
                contentType: false,
                success: function(){
                    location.reload();
                },
                error: function(error){
                    if (error.status == 422) {
                        showErrorNotification(error.responseJSON.errors);
                    }else{
                        showNotification('danger', 'Terjadi kesalahan silahkan refresh dan coba lagi');
                    }
                    buttonSubmit.removeClass('is-loading').attr('disabled', false);
                }
            });
        }
    });

    $('select[name=grade]').on('change', function(e){
        var grade = $(this).val();
        changeLevelOptions(grade);
    });

    function changeLevelOptions(grade){
        var select_level = $('select[name=level]');
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
    }

    $(document).ready(function(){
        changeLevelOptions('{{ $employee->grade }}');
        $('select[name=level]').val({{ $employee->level }}).trigger('change');
    });

    $('select[name=division_id]').on('change', function(){
        var id = $(this).val();
        var select_departments = $('select[name=department_id]');
        select_departments.empty();

        $.ajax({
            url: '{{ route('division.departments', '') }}/'+id,
            type: 'GET',
            dataType: 'JSON',
            beforeSend: function(){
                select_departments.attr('disabled', true);
            },
            success: function(resp){
                select_departments.select2({
                    theme: 'bootstrap',
                    data: resp
                });
                select_departments.attr('disabled', false);
                select_departments.trigger('change');
            },
            error: function(){
                showSwal('error', 'Perhatian!', 'Gagal mengambil data departemen');
            },
            complete: function(){
                select_departments.attr('disabled', false);
            }
        });
    });

    $('#photo').on('change', function(){
        if ($(this).val() == '') {
            $(this).prev('.img-upload-preview').attr('src', '{{ asset('uploads/images/users/'.$employee->photo) ?? asset('uploads/images/profile-avatar-flat.png') }}');
        }
    });
</script>
@endsection