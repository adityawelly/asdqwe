@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Profil Saya</h4>
            {{ Breadcrumbs::render('account') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-profile">
                                <div class="card-header" style="background-image: url('{{ asset('img/blogpost.jpg') }}')">
                                    <div class="profile-picture">
                                        <div class="avatar avatar-xl">
                                            <a href="{{ empty($user->employee->photo) ? 
                                                    asset('uploads/images/profile-avatar-flat.png'):
                                                    asset('uploads/images/users/'.$user->employee->photo) }}"
                                                    data-lightbox="{{ time() }}" data-title="Foto Diri">
                                            <img src="{{ empty($user->employee->photo) ? 
                                                    asset('uploads/images/profile-avatar-flat.png'):
                                                    asset('uploads/images/users/'.$user->employee->photo) }}" alt="Foto Profil"
                                                    class="avatar-img rounded-circle">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="user-profile text-center">
                                        <div class="name">{{ $user->employee->fullname }}</div>
                                        <div class="job">{{ $user->employee->grade_title->grade_title_name }}</div>
                                        {{-- <div class="desc">{{ $user->employee->division->division_name }}</div> --}}
                                    </div>
                                </div>
                                {{-- <div class="card-footer">
                                    <div class="row user-stats text-center">
                                        <div class="col">
                                            <div class="number">125</div>
                                            <div class="title">Post</div>
                                        </div>
                                        <div class="col">
                                            <div class="number">25K</div>
                                            <div class="title">Followers</div>
                                        </div>
                                        <div class="col">
                                            <div class="number">134</div>
                                            <div class="title">Following</div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-primary">
                                <h5 class="card-title">Informasi Posisi</h5>
                            </div>
                            <div class="card-body">
                                <p>Divisi : {{ $user->employee->division->division_name }}</p>
                                <p>Direct Superior : {{ $user->employee->superior->fullname ?? 'Belum Diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Diri</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nama Lengkap</dt>
                                    <dd>{{ $user->employee->fullname }}</dd>
                                    <dt>Tempat, Tanggal Lahir</dt>
                                    <dd>{{ $user->employee->employee_detail->place_of_birth.', '.date('d/m/Y', strtotime($user->employee->employee_detail->date_of_birth)) }}</dd>
                                    <dt>No KTP</dt>
                                    <dd>{{ $user->employee->employee_detail->ID_number }}</dd>
                                    <dt>Jenis Kelamin</dt>
                                    <dd>{{ $user->employee->employee_detail->sex }}</dd>
                                    <dt>No Telepon</dt>
                                    <dd>{{ $user->employee->employee_detail->phone_number }}</dd>
                                    <dt>Pendidikan Terakhir</dt>
                                    <dd>{{ $user->employee->employee_detail->last_education }}</dd>
                                    <dt>Jurusan</dt>
                                    <dd>{{ $user->employee->employee_detail->education_focus }}</dd>
                                    <dt>Alamat</dt>
                                    <dd>{{ $user->employee->employee_detail->address }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Nomor Induk Karyawan (NIK)</dt>
                                    <dd>{{ $user->employee->registration_number }}</dd>
                                    <dt>Tanggal Mulai Bekerja</dt>
                                    <dd>{{ date('d/m/Y', strtotime($user->employee->date_of_work)) }} ({{ $user->employee->year_of_service }})</dd>
                                    <dt>Divisi</dt>
                                    <dd>{{ $user->employee->division->division_code }} - {{ $user->employee->division->division_name }}</dd>
                                    <dt>Departemen</dt>
                                    <dd>{{ $user->employee->department->department_code }} - {{ $user->employee->department->department_name }}</dd>
                                    <dt>Grade Title</dt>
                                    <dd>{{ $user->employee->grade_title->grade_title_code }} - {{ $user->employee->grade_title->grade_title_name }}</dd>
                                    <dt>Level Title</dt>
                                    <dd>{{ $user->employee->level_title->level_title_code }} - {{ $user->employee->level_title->level_title_name }}</dd>
                                    <dt>Job Title</dt>
                                    <dd>{{ $user->employee->job_title->job_title_code }} - {{ $user->employee->job_title->job_title_name }}</dd>
                                    <dt>Lokasi</dt>
                                    <dd>{{ $user->employee->company_region->region_city }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        Jika ada perubahan data, silahkan menghubungi admin, thanks!.
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Payroll</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl>
                            <dt>Email</dt>
                            <dd>{{ $user->employee->user->email }}</dd>
                            <dt>Bank</dt>
                            <dd>
                                {{ $user->employee->employee_salary->bank ?? 'Belum diisi' }}
                            </dd>
                            <dt>Nomor Rekening</dt>
                            <dd>
                                {{ $user->employee->employee_salary->bank_account_number ?? 'Belum diisi' }}
                            </dd>
                            <dt>Salary</dt>
                            <dd>
                                Rp. {{ $user->employee->employee_salary->basic_salary }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('account.update') }}" method="post" enctype="multipart/form-data" id="formUserAccount">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="">Email <span class="required-label">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password" required minlength="6" placeholder="Kosongkan jika tidak ingin diubah">
                            </div>
                            <div class="form-group">
                                <label for="">Upload foto diri</label>
                                <div class="input-file input-file-image">
                                    <a href="{{ asset('uploads/images/users/'.($user->employee->photo ?? '../profile-avatar-flat.png')) }}"
                                        data-lightbox="{{ time() }}" data-title="Foto Diri">
                                        <img class="img-upload-preview img-circle ml-auto mr-auto" width="150" height="150" src="{{ asset('uploads/images/users/'.($user->employee->photo ?? '../profile-avatar-flat.png')) }}" alt="preview">
                                    </a>
                                    <input type="file" class="form-control form-control-file" id="photo" name="photo" accept="image/*">
                                    <center><label for="photo" class="btn btn-secondary btn-round btn-sm" style="color:#fff !important"><i class="fa fa-file-image"></i> Upload foto</label></center>
                                    <small class="form-text text-muted">*Maksimal 512kb, format image(.jpg/.png)</small>
                                </div>    
                            </div>
                            <div class="form-group">
                                <hr>
                                <button type="submit" class="btn btn-md btn-success btn-round pull-right"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header card-primary">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Riwayat Aktifitas</h4>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var form = $('#formUserAccount');

    function passwordRequired() {
        return $('input[name=password]').val().length > 0;
    }

    form.validate({
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
            form.find('button[type=submit]').addClass('is-loading').attr('disabled', true);
            form.submit();
        }
    });

    $('#photo').on('change', function(){
        if ($(this).val() == '') {
            $(this).prev('.img-upload-preview').attr('src', '{{ asset('uploads/images/users/'.($user->employee->photo ?? 'profile-avatar-flat.png')) }}');
        }
    });
</script>
@endsection